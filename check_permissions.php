<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$modules = App\Models\ModulePermission::all();

echo "Total de módulos: " . $modules->count() . "\n\n";

foreach ($modules as $module) {
    echo "Módulo: {$module->module_name}\n";
    echo "  - Super Admin: " . ($module->super_admin ? 'Sí' : 'No') . "\n";
    echo "  - Admin: " . ($module->admin ? 'Sí' : 'No') . "\n";
    echo "  - Gerente Bodega: " . ($module->gerente_bodega ? 'Sí' : 'No') . "\n";
    echo "  - Supervisor: " . ($module->supervisor ? 'Sí' : 'No') . "\n";
    echo "  - Empleado: " . ($module->empleado ? 'Sí' : 'No') . "\n\n";
}
