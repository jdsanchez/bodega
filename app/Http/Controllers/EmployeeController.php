<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with(['supervisor', 'warehouse'])->latest()->paginate(10);
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Employee::getRoles();
        $statuses = Employee::getStatuses();
        $warehouses = Warehouse::where('status', 'activa')->get();
        $supervisors = Employee::where('is_active', true)
            ->whereIn('role', ['jefe', 'gerente', 'administrador', 'encargado_bodega'])
            ->get();
        return view('employees.create', compact('roles', 'statuses', 'warehouses', 'supervisors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dpi' => 'required|string|unique:employees,dpi',
            'nit' => 'nullable|string',
            'phone' => 'nullable|string',
            'mobile' => 'nullable|string',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'hire_date' => 'required|date',
            'start_date' => 'nullable|date',
            'role' => 'required|in:' . implode(',', array_keys(Employee::getRoles())),
            'supervisor_id' => 'nullable|exists:employees,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'status' => 'required|in:' . implode(',', array_keys(Employee::getStatuses())),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Empleado creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load('supervisor', 'subordinates');
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $roles = Employee::getRoles();
        $statuses = Employee::getStatuses();
        $warehouses = Warehouse::where('status', 'activa')->get();
        $supervisors = Employee::where('is_active', true)
            ->where('id', '!=', $employee->id)
            ->whereIn('role', ['jefe', 'gerente', 'administrador', 'encargado_bodega'])
            ->get();
        return view('employees.edit', compact('employee', 'roles', 'statuses', 'warehouses', 'supervisors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dpi' => 'required|string|unique:employees,dpi,' . $employee->id,
            'nit' => 'nullable|string',
            'phone' => 'nullable|string',
            'mobile' => 'nullable|string',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'hire_date' => 'required|date',
            'start_date' => 'nullable|date',
            'role' => 'required|in:' . implode(',', array_keys(Employee::getRoles())),
            'supervisor_id' => 'nullable|exists:employees,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'status' => 'required|in:' . implode(',', array_keys(Employee::getStatuses())),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Eliminar foto si existe
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Empleado eliminado exitosamente.');
    }
}
