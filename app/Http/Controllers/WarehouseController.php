<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Warehouse::with('manager');

        // Filtro por nombre
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filtro por dirección/ubicación
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }

        // Filtro por teléfono
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        // Filtro por encargado
        if ($request->filled('manager_id')) {
            $query->where('manager_id', $request->manager_id);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $warehouses = $query->orderBy('name')->paginate(10)->withQueryString();
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();

        return view('warehouses.index', compact('warehouses', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('is_active', true)
            ->orderBy('first_name')
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'text' => $employee->full_name . ' - ' . $employee->role_name
                ];
            });

        return view('warehouses.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:50',
            'manager_id' => 'nullable|exists:employees,id',
            'whatsapp' => 'nullable|string|max:50',
            'google_maps_url' => 'nullable|url',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:activa,inactiva,mantenimiento',
            'email' => 'nullable|email|max:255',
        ]);

        // Subir foto si existe
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('warehouses', 'public');
        }

        Warehouse::create($validated);

        return redirect()->route('warehouses.index')->with('success', 'Bodega creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse->load('manager');
        return view('warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        $employees = Employee::where('is_active', true)
            ->orderBy('first_name')
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'text' => $employee->full_name . ' - ' . $employee->role_name
                ];
            });

        return view('warehouses.edit', compact('warehouse', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:50',
            'manager_id' => 'nullable|exists:employees,id',
            'whatsapp' => 'nullable|string|max:50',
            'google_maps_url' => 'nullable|url',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:activa,inactiva,mantenimiento',
            'email' => 'nullable|email|max:255',
        ]);

        // Subir nueva foto si existe
        if ($request->hasFile('photo')) {
            // Eliminar foto anterior
            if ($warehouse->photo) {
                Storage::disk('public')->delete($warehouse->photo);
            }
            $validated['photo'] = $request->file('photo')->store('warehouses', 'public');
        }

        $warehouse->update($validated);

        return redirect()->route('warehouses.index')->with('success', 'Bodega actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        // Eliminar foto si existe
        if ($warehouse->photo) {
            Storage::disk('public')->delete($warehouse->photo);
        }

        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('success', 'Bodega eliminada exitosamente.');
    }

    /**
     * API endpoint para buscar empleados (para Select2)
     */
    public function searchEmployees(Request $request)
    {
        $search = $request->get('q');
        
        $employees = Employee::where('is_active', true)
            ->where(function($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orderBy('first_name')
            ->limit(10)
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'text' => $employee->full_name . ' - ' . $employee->role_name
                ];
            });

        return response()->json(['results' => $employees]);
    }
}
