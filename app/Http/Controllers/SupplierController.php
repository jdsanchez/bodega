<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::notDeleted()
            ->orderBy('name')
            ->paginate(10);
        
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $statuses = Supplier::getStatuses();
        return view('suppliers.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'required|string|unique:suppliers,nit',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:activo,inactivo',
        ]);

        $data = $request->except('logo');

        // Subir logo si existe
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('suppliers', 'public');
        }

        Supplier::create($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function show(Supplier $supplier)
    {
        if ($supplier->status === 'eliminado') {
            abort(404);
        }
        
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        if ($supplier->status === 'eliminado') {
            abort(404);
        }

        $statuses = Supplier::getStatuses();
        return view('suppliers.edit', compact('supplier', 'statuses'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'required|string|unique:suppliers,nit,' . $supplier->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:activo,inactivo',
        ]);

        $data = $request->except('logo');

        // Subir nuevo logo si existe
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior
            if ($supplier->logo) {
                Storage::disk('public')->delete($supplier->logo);
            }
            $data['logo'] = $request->file('logo')->store('suppliers', 'public');
        }

        $supplier->update($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Supplier $supplier)
    {
        // Marcar como eliminado en lugar de eliminar físicamente
        $supplier->update(['status' => 'eliminado']);

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
