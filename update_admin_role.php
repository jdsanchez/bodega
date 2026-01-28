<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// Buscar el usuario admin
$user = User::where('email', 'admin@bodegapeniel.com')->first();

if ($user) {
    echo "Usuario encontrado: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Rol actual: {$user->role}\n\n";
    
    // Actualizar a super_admin
    $user->role = 'super_admin';
    $user->save();
    
    echo "Rol actualizado a: {$user->role}\n";
    echo "Nombre del rol: {$user->role_name}\n";
} else {
    echo "Usuario no encontrado.\n";
}
