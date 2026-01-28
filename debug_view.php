<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Obtener los datos como lo hace el controlador
$roles = [
    'super_admin' => 'Super Administrador',
    'admin' => 'Administrador',
    'gerente_bodega' => 'Gerente de Bodega',
    'supervisor' => 'Supervisor',
    'empleado' => 'Empleado'
];

$modules = App\Models\ModulePermission::orderBy('order')->get();

echo "Simulando la vista:\n\n";
echo "Roles: " . implode(', ', array_keys($roles)) . "\n\n";

foreach ($modules as $module) {
    echo "Módulo: {$module->module_name} (ID: {$module->id})\n";
    foreach ($roles as $roleKey => $roleName) {
        $checked = $module->$roleKey ? 'checked' : 'unchecked';
        $value = $module->$roleKey ?? 'NULL';
        echo "  - {$roleKey}: {$checked} (valor: {$value})\n";
    }
    echo "\n";
}
