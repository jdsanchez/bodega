<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TextileTypeController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReceptionController;
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
    
    // Rutas de tipos de producto
    Route::resource('product-types', ProductTypeController::class);
    
    // Rutas de inventario
    Route::resource('inventory', InventoryController::class);
    Route::get('/inventory/export', [InventoryController::class, 'export'])->name('inventory.export');
    Route::get('/inventory/template', [InventoryController::class, 'template'])->name('inventory.template');
    Route::post('/inventory/import', [InventoryController::class, 'import'])->name('inventory.import');
    Route::post('/inventory/{id}/restore', [InventoryController::class, 'restore'])->name('inventory.restore');
    Route::delete('/inventory/{id}/force-delete', [InventoryController::class, 'forceDelete'])->name('inventory.forceDelete');
    
    // Rutas de recepciones
    Route::resource('receptions', ReceptionController::class);
    Route::get('/receptions/{reception}/receive', [ReceptionController::class, 'receive'])->name('receptions.receive');
    Route::post('/receptions/{reception}/process', [ReceptionController::class, 'processReceipt'])->name('receptions.process');
    Route::post('/receptions/{reception}/reject', [ReceptionController::class, 'reject'])->name('receptions.reject');
});

require __DIR__.'/auth.php';
