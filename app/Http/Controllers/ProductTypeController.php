<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductType::with('supplier');

        // Filtro por nombre
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filtro por proveedor
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $productTypes = $query->latest()->paginate(12)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();
        
        return view('product-types.index', compact('productTypes', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('product-types.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('product-types', 'public');
        }

        ProductType::create($validated);

        return redirect()->route('product-types.index')->with('success', 'Tipo de producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductType $productType)
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('product-types.edit', compact('productType', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductType $productType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($productType->photo) {
                Storage::disk('public')->delete($productType->photo);
            }
            $validated['photo'] = $request->file('photo')->store('product-types', 'public');
        }

        $productType->update($validated);

        return redirect()->route('product-types.index')->with('success', 'Tipo de producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType)
    {
        // Eliminar foto si existe
        if ($productType->photo) {
            Storage::disk('public')->delete($productType->photo);
        }

        $productType->delete();

        return redirect()->route('product-types.index')->with('success', 'Tipo de producto eliminado exitosamente.');
    }
}
