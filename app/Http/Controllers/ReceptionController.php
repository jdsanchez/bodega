<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use App\Models\ReceptionItem;
use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\ProductType;
use App\Models\TextileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Reception::with(['warehouse', 'supplier', 'creator', 'items']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('reception_number', 'like', "%{$request->search}%")
                  ->orWhere('invoice_number', 'like', "%{$request->search}%");
            });
        }

        $receptions = $query->latest()->paginate(15)->withQueryString();
        $warehouses = Warehouse::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        $stats = [
            'pending' => Reception::where('status', 'pendiente')->count(),
            'in_review' => Reception::where('status', 'en_revision')->count(),
            'received' => Reception::where('status', 'recibida')->count(),
            'total_value' => Reception::where('status', 'recibida')->sum('total_received'),
        ];

        return view('receptions.index', compact('receptions', 'warehouses', 'suppliers', 'stats'));
    }

    public function create()
    {
        $warehouses = Warehouse::active()->orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $textileTypes = TextileType::active()->orderBy('name')->get();

        return view('receptions.create', compact('warehouses', 'suppliers', 'productTypes', 'textileTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string|max:255',
            'purchase_order' => 'nullable|string|max:255',
            'expected_date' => 'required|date',
            'notes' => 'nullable|string',
            
            'items' => 'required|array|min:1',
            'items.*.product_type_id' => 'required|exists:product_types,id',
            'items.*.textile_type_id' => 'nullable|exists:textile_types,id',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.expected_quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            $reception = Reception::create($validated);
            foreach ($request->items as $itemData) {
                $reception->items()->create($itemData);
            }
            $reception->calculateTotals();
            DB::commit();

            return redirect()->route('receptions.show', $reception)->with('success', 'Recepción creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show(Reception $reception)
    {
        $reception->load(['warehouse.manager', 'supplier', 'items.productType', 'items.textileType', 'creator', 'receiver', 'approver']);
        return view('receptions.show', compact('reception'));
    }

    public function edit(Reception $reception)
    {
        if (!in_array($reception->status, ['pendiente', 'en_revision'])) {
            return redirect()->route('receptions.show', $reception)->with('error', 'No se puede editar esta recepción.');
        }

        $reception->load('items');
        $warehouses = Warehouse::active()->orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $textileTypes = TextileType::active()->orderBy('name')->get();

        return view('receptions.edit', compact('reception', 'warehouses', 'suppliers', 'productTypes', 'textileTypes'));
    }

    public function update(Request $request, Reception $reception)
    {
        if (!in_array($reception->status, ['pendiente', 'en_revision'])) {
            return back()->with('error', 'No se puede editar esta recepción.');
        }

        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string|max:255',
            'purchase_order' => 'nullable|string|max:255',
            'expected_date' => 'required|date',
            'notes' => 'nullable|string',
            'existing_items' => 'nullable|array',
            'items' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // Update reception header
            $reception->update([
                'warehouse_id' => $validated['warehouse_id'],
                'supplier_id' => $validated['supplier_id'],
                'invoice_number' => $validated['invoice_number'] ?? null,
                'purchase_order' => $validated['purchase_order'] ?? null,
                'expected_date' => $validated['expected_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update existing items
            if ($request->has('existing_items')) {
                foreach ($request->existing_items as $itemId => $itemData) {
                    $item = $reception->items()->find($itemId);
                    if ($item) {
                        $item->update($itemData);
                    }
                }
            }

            // Add new items
            if ($request->has('items')) {
                foreach ($request->items as $itemData) {
                    $reception->items()->create($itemData);
                }
            }

            $reception->calculateTotals();
            DB::commit();

            return redirect()->route('receptions.show', $reception)->with('success', 'Recepción actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar la recepción: ' . $e->getMessage());
        }
    }

    public function destroy(Reception $reception)
    {
        if ($reception->status === 'recibida') {
            return back()->with('error', 'No se puede eliminar una recepción ya recibida.');
        }

        $reception->delete();
        return redirect()->route('receptions.index')->with('success', 'Recepción eliminada.');
    }

    public function receive(Reception $reception)
    {
        if ($reception->status === 'recibida') {
            return redirect()->route('receptions.show', $reception)->with('error', 'Esta recepción ya fue procesada.');
        }

        $reception->load('items.productType', 'warehouse', 'supplier');
        return view('receptions.receive', compact('reception'));
    }

    public function processReceipt(Request $request, Reception $reception)
    {
        $validated = $request->validate([
            'reception_date' => 'required|date',
            'items' => 'required|array',
            'items.*.received_quantity' => 'required|numeric|min:0',
            'items.*.condition' => 'required|in:bueno,dañado,defectuoso,incompleto',
            'items.*.location' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $reception->reception_date = $validated['reception_date'];
            $reception->received_by = auth()->id();

            $allComplete = true;
            foreach ($request->items as $itemId => $itemData) {
                $item = $reception->items()->find($itemId);
                if ($item) {
                    $item->update($itemData);
                    if ($itemData['received_quantity'] < $item->expected_quantity) {
                        $allComplete = false;
                    }
                }
            }

            $reception->status = $allComplete ? 'recibida' : 'parcial';
            $reception->save();
            $reception->calculateTotals();

            DB::commit();
            return redirect()->route('receptions.show', $reception)->with('success', 'Recepción procesada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Reception $reception)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $reception->update([
            'status' => 'rechazada',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('receptions.show', $reception)->with('success', 'Recepción rechazada.');
    }
}
