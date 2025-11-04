<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $employees = Employee::where('is_active', true)
            ->orderBy('first_name')
            ->get();
        
        $today = Carbon::today();
        
        return view('attendance.index', compact('employees', 'today'));
    }

    public function checkIn(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:check_in,lunch_out,lunch_in,check_out'
        ]);

        $employee = Employee::findOrFail($id);
        $today = Carbon::today();
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        $type = $request->input('type');

        // Verificar si ya existe un registro de hoy
        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'date' => $today
            ]
        );

        $message = '';
        $success = true;

        // Registrar según el tipo seleccionado
        switch ($type) {
            case 'check_in':
                if ($attendance->check_in) {
                    $message = "Ya tienes una entrada registrada hoy a las " . Carbon::parse($attendance->check_in)->format('h:i A');
                    $success = false;
                } else {
                    $attendance->check_in = $currentTime;
                    $message = "Entrada registrada: {$now->format('h:i A')}";
                }
                break;

            case 'lunch_out':
                if (!$attendance->check_in) {
                    $message = "Primero debes registrar tu entrada";
                    $success = false;
                } elseif ($attendance->lunch_out) {
                    $message = "Ya tienes una salida a almuerzo registrada hoy a las " . Carbon::parse($attendance->lunch_out)->format('h:i A');
                    $success = false;
                } else {
                    $attendance->lunch_out = $currentTime;
                    $message = "Salida a almuerzo registrada: {$now->format('h:i A')}";
                }
                break;

            case 'lunch_in':
                if (!$attendance->lunch_out) {
                    $message = "Primero debes registrar tu salida a almuerzo";
                    $success = false;
                } elseif ($attendance->lunch_in) {
                    $message = "Ya tienes un regreso de almuerzo registrado hoy a las " . Carbon::parse($attendance->lunch_in)->format('h:i A');
                    $success = false;
                } else {
                    $attendance->lunch_in = $currentTime;
                    $message = "Regreso de almuerzo registrado: {$now->format('h:i A')}";
                }
                break;

            case 'check_out':
                if (!$attendance->check_in) {
                    $message = "Primero debes registrar tu entrada";
                    $success = false;
                } elseif ($attendance->check_out) {
                    $message = "Ya tienes una salida registrada hoy a las " . Carbon::parse($attendance->check_out)->format('h:i A');
                    $success = false;
                } else {
                    $attendance->check_out = $currentTime;
                    $message = "Salida registrada: {$now->format('h:i A')}";
                }
                break;
        }

        if ($success) {
            $attendance->save();
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'type' => $type,
            'employee' => $employee->full_name,
            'attendance' => $attendance
        ]);
    }

    public function history()
    {
        $attendances = Attendance::with('employee')
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->paginate(20);
        
        return view('attendance.history', compact('attendances'));
    }

    public function report(Request $request)
    {
        $query = Attendance::with('employee');

        // Filtro por empleado
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filtro por fecha inicio
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        // Filtro por fecha fin
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Filtro por estado (completo, incompleto)
        if ($request->filled('status')) {
            if ($request->status === 'complete') {
                $query->whereNotNull('check_in')
                      ->whereNotNull('check_out');
            } elseif ($request->status === 'incomplete') {
                $query->where(function($q) {
                    $q->whereNull('check_in')
                      ->orWhereNull('check_out');
                });
            }
        }

        $attendances = $query->orderBy('date', 'desc')
                            ->orderBy('check_in', 'desc')
                            ->paginate(20)
                            ->appends($request->query());

        // Obtener lista de empleados para el filtro
        $employees = Employee::where('is_active', true)
                            ->orderBy('first_name')
                            ->get();

        return view('attendance.report', compact('attendances', 'employees'));
    }
}
