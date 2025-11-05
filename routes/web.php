<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TextileTypeController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de usuarios
    Route::resource('users', UserController::class);
    
    // Rutas de empleados
    Route::resource('employees', EmployeeController::class);
    
    // Rutas de asistencia
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in/{id}', [AttendanceController::class, 'checkIn'])->name('attendance.checkIn');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
    
    // Rutas de proveedores
    Route::resource('suppliers', SupplierController::class);
    
    // Rutas de tipos de textiles
    Route::resource('textile-types', TextileTypeController::class);
    
    // Rutas de bodegas
    Route::resource('warehouses', WarehouseController::class);
    
    // Rutas de contactos
    Route::resource('contacts', ContactController::class);
});

require __DIR__.'/auth.php';
