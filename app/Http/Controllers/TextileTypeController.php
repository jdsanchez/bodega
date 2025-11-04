<?php

namespace App\Http\Controllers;

use App\Models\TextileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TextileTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TextileType::query();

        // Filtro por nombre
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filtro por material
        if ($request->filled('material')) {
            $query->where('material', 'like', '%' . $request->material . '%');
        }

        // Filtro por color
        if ($request->filled('color')) {
            $query->where('color', 'like', '%' . $request->color . '%');
        }

        // Filtro por estado
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        $textileTypes = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('textile-types.index', compact('textileTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('textile-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'material' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        // Subir foto si existe
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('textiles', 'public');
        }

        TextileType::create($validated);

        return redirect()->route('textile-types.index')->with('success', 'Tipo de textil creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TextileType $textileType)
    {
        return view('textile-types.show', compact('textileType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TextileType $textileType)
    {
        return view('textile-types.edit', compact('textileType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TextileType $textileType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'material' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        // Subir nueva foto si existe
        if ($request->hasFile('photo')) {
            // Eliminar foto anterior
            if ($textileType->photo) {
                Storage::disk('public')->delete($textileType->photo);
            }
            $validated['photo'] = $request->file('photo')->store('textiles', 'public');
        }

        $textileType->update($validated);

        return redirect()->route('textile-types.index')->with('success', 'Tipo de textil actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TextileType $textileType)
    {
        // Eliminar foto si existe
        if ($textileType->photo) {
            Storage::disk('public')->delete($textileType->photo);
        }

        $textileType->delete();

        return redirect()->route('textile-types.index')->with('success', 'Tipo de textil eliminado exitosamente.');
    }
}
