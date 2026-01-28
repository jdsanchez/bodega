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
    
    // Rutas solo para Super Admin
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/permissions', [App\Http\Controllers\PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions/update', [App\Http\Controllers\PermissionController::class, 'updatePermission'])->name('permissions.update');
        Route::post('/permissions/reset', [App\Http\Controllers\PermissionController::class, 'resetToDefault'])->name('permissions.reset');
        Route::resource('users', UserController::class);
    });
    
    // Rutas para Admin y Super Admin
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('contacts', ContactController::class);
        Route::resource('suppliers', SupplierController::class);
        
        // Asistencias (crear/editar)
        Route::post('/attendance/check-in/{id}', [AttendanceController::class, 'checkIn'])->name('attendance.checkIn');
    });
    
    // Rutas para gestión de inventario (Super Admin, Admin, Gerente Bodega)
    Route::middleware(['role:super_admin,admin,gerente_bodega'])->group(function () {
        Route::resource('inventory', InventoryController::class);
        Route::post('/inventory/import', [InventoryController::class, 'import'])->name('inventory.import');
        Route::post('/inventory/{id}/restore', [InventoryController::class, 'restore'])->name('inventory.restore');
        Route::delete('/inventory/{id}/force-delete', [InventoryController::class, 'forceDelete'])->name('inventory.forceDelete');
        
        Route::resource('receptions', ReceptionController::class);
        Route::get('/receptions/{reception}/receive', [ReceptionController::class, 'receive'])->name('receptions.receive');
        Route::post('/receptions/{reception}/process', [ReceptionController::class, 'processReceipt'])->name('receptions.process');
        Route::post('/receptions/{reception}/reject', [ReceptionController::class, 'reject'])->name('receptions.reject');
        
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('textile-types', TextileTypeController::class);
        Route::resource('product-types', ProductTypeController::class);
    });
    
    // Rutas de solo lectura (todos pueden ver excepto empleado básico)
    Route::middleware(['role:super_admin,admin,gerente_bodega,supervisor'])->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
        Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
        
        Route::get('/inventory/export', [InventoryController::class, 'export'])->name('inventory.export');
        Route::get('/inventory/template', [InventoryController::class, 'template'])->name('inventory.template');
    });
});

require __DIR__.'/auth.php';
