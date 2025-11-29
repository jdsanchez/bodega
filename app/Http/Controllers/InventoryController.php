<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\ProductType;
use App\Models\Warehouse;
use App\Models\TextileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::with(['productType', 'warehouse', 'textileType', 'creator', 'updater']);

        // Filtro por búsqueda general (nombre, SKU, código de barras, ubicación)
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtro por bodega
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        // Filtro por tipo de producto
        if ($request->filled('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }

        // Filtro por tipo de textil
        if ($request->filled('textile_type_id')) {
            $query->where('textile_type_id', $request->textile_type_id);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por rango de precio
        if ($request->filled('min_price')) {
            $query->where('unit_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('unit_price', '<=', $request->max_price);
        }

        // Filtro por stock bajo
        if ($request->filled('low_stock') && $request->low_stock) {
            $query->lowStock();
        }

        // Ordenamiento
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $inventories = $query->paginate(15)->withQueryString();

        // Datos para los filtros
        $warehouses = Warehouse::orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $textileTypes = TextileType::orderBy('name')->get();

        // Estadísticas
        $stats = [
            'total_items' => Inventory::count(),
            'total_value' => Inventory::sum('total_price'),
            'low_stock_count' => Inventory::lowStock()->count(),
            'out_of_stock_count' => Inventory::outOfStock()->count(),
        ];

        return view('inventory.index', compact(
            'inventories',
            'warehouses',
            'productTypes',
            'textileTypes',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::active()->orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $textileTypes = TextileType::active()->orderBy('name')->get();

        return view('inventory.create', compact('warehouses', 'productTypes', 'textileTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_type_id' => 'required|exists:product_types,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'textile_type_id' => 'nullable|exists:textile_types,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|unique:inventories,barcode|max:255',
            'sku' => 'required|string|unique:inventories,sku|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:disponible,reservado,agotado,en_transito,dañado',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Procesar múltiples fotos
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('inventory', 'public');
                $photos[] = $path;
            }
        }
        $validated['photos'] = $photos;

        try {
            $inventory = Inventory::create($validated);

            return redirect()
                ->route('inventory.show', $inventory)
                ->with('success', 'Producto agregado al inventario exitosamente.');
        } catch (\Exception $e) {
            // Eliminar fotos si hubo error
            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo);
            }

            return back()
                ->withInput()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        $inventory->load([
            'productType',
            'warehouse.manager',
            'textileType',
            'creator',
            'updater',
            'deleter'
        ]);

        return view('inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        $warehouses = Warehouse::active()->orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $textileTypes = TextileType::active()->orderBy('name')->get();

        return view('inventory.edit', compact('inventory', 'warehouses', 'productTypes', 'textileTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'product_type_id' => 'required|exists:product_types,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'textile_type_id' => 'nullable|exists:textile_types,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|max:255|unique:inventories,barcode,' . $inventory->id,
            'sku' => 'required|string|max:255|unique:inventories,sku,' . $inventory->id,
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:disponible,reservado,agotado,en_transito,dañado',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_photos' => 'nullable|array',
            'remove_photos.*' => 'string',
        ]);

        try {
            // Manejar fotos existentes
            $currentPhotos = $inventory->photos ?? [];

            // Eliminar fotos marcadas para remover
            if ($request->filled('remove_photos')) {
                foreach ($request->remove_photos as $photoToRemove) {
                    if (in_array($photoToRemove, $currentPhotos)) {
                        Storage::disk('public')->delete($photoToRemove);
                        $currentPhotos = array_values(array_diff($currentPhotos, [$photoToRemove]));
                    }
                }
            }

            // Agregar nuevas fotos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('inventory', 'public');
                    $currentPhotos[] = $path;
                }
            }

            $validated['photos'] = $currentPhotos;

            $inventory->update($validated);

            return redirect()
                ->route('inventory.show', $inventory)
                ->with('success', 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        try {
            // El soft delete se maneja automáticamente en el modelo
            $inventory->delete();

            return redirect()
                ->route('inventory.index')
                ->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Restaurar un producto eliminado
     */
    public function restore($id)
    {
        $inventory = Inventory::withTrashed()->findOrFail($id);
        $inventory->restore();

        return redirect()
            ->route('inventory.show', $inventory)
            ->with('success', 'Producto restaurado exitosamente.');
    }

    /**
     * Eliminar permanentemente un producto
     */
    public function forceDelete($id)
    {
        $inventory = Inventory::withTrashed()->findOrFail($id);

        // Eliminar todas las fotos
        if ($inventory->photos) {
            foreach ($inventory->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $inventory->forceDelete();

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Producto eliminado permanentemente.');
    }

    /**
     * Exportar inventario a CSV
     */
    public function export(Request $request)
    {
        $query = Inventory::with(['productType', 'warehouse', 'textileType']);

        // Aplicar los mismos filtros que en index
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inventories = $query->get();

        $filename = 'inventario_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($inventories) {
            $file = fopen('php://output', 'w');
            
            // Encabezados
            fputcsv($file, [
                'SKU',
                'Nombre',
                'Tipo de Producto',
                'Bodega',
                'Tipo de Textil',
                'Cantidad',
                'Unidad',
                'Precio Unitario',
                'Precio Total',
                'Código de Barras',
                'Ubicación',
                'Estado',
                'Notas',
                'Creado por',
                'Fecha de Creación',
            ]);

            // Datos
            foreach ($inventories as $item) {
                fputcsv($file, [
                    $item->sku,
                    $item->name,
                    $item->productType->name ?? 'N/A',
                    $item->warehouse->name ?? 'N/A',
                    $item->textileType->name ?? 'N/A',
                    $item->quantity,
                    $item->unit,
                    $item->unit_price,
                    $item->total_price,
                    $item->barcode ?? '',
                    $item->location ?? '',
                    $item->status_label,
                    $item->creator->name ?? 'N/A',
                    $item->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
