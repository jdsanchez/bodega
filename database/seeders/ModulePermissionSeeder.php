<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModulePermission;

class ModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultModules = [
            ['module_key' => 'dashboard', 'module_name' => 'Dashboard', 'module_icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'super_admin' => true, 'admin' => true, 'gerente_bodega' => true, 'supervisor' => true, 'empleado' => true, 'order' => 1],
            ['module_key' => 'users', 'module_name' => 'Usuarios', 'module_icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'super_admin' => true, 'admin' => false, 'gerente_bodega' => false, 'supervisor' => false, 'empleado' => false, 'order' => 2],
            ['module_key' => 'employees', 'module_name' => 'Empleados', 'module_icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'super_admin' => true, 'admin' => true, 'gerente_bodega' => false, 'supervisor' => false, 'empleado' => false, 'order' => 3],
            ['module_key' => 'attendance', 'module_name' => 'Asistencia', 'module_icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'super_admin' => true, 'admin' => true, 'gerente_bodega' => false, 'supervisor' => true, 'empleado' => false, 'order' => 4],
            ['module_key' => 'contacts', 'module_name' => 'Contactos', 'module_icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'super_admin' => true, 'admin' => true, 'gerente_bodega' => false, 'supervisor' => false, 'empleado' => false, 'order' => 5],
            ['module_key' => 'suppliers', 'module_name' => 'Proveedores', 'module_icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'super_admin' => true, 'admin' => true, 'gerente_bodega' => false, 'supervisor' => false, 'empleado' => false, 'order' => 6],
            ['module_key' => 'receptions', 'module_name' => 'Recepciones', 'module_icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4', 'super_admin' => true, 'admin' => true, 'gerente_bodega' => true, 'supervisor' => true, 'empleado' => false, 'order' => 7],
            ['module_key' => 'inventory', 'module_name' => 'Inventario', 'module_icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'super_admin' => true, 'admin' => true, 'gerente_bodega' => true, 'supervisor' => true, 'empleado' => false, 'order' => 8],
            ['module_key' => 'warehouses', 'module_name' => 'Bodegas', 'module_icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'super_admin' => true, 'admin' => true, 'gerente_bodega' => true, 'supervisor' => true, 'empleado' => false, 'order' => 9],
            ['module_key' => 'textile-types', 'module_name' => 'Tipos de Textiles', 'module_icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'super_admin' => true, 'admin' => true, 'gerente_bodega' => true, 'supervisor' => true, 'empleado' => false, 'order' => 10],
            ['module_key' => 'product-types', 'module_name' => 'Tipos de Productos', 'module_icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'super_admin' => true, 'admin' => true, 'gerente_bodega' => true, 'supervisor' => true, 'empleado' => false, 'order' => 11],
        ];

        foreach ($defaultModules as $module) {
            ModulePermission::updateOrCreate(
                ['module_key' => $module['module_key']],
                $module
            );
        }
    }
}
