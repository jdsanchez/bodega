<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin@bodegapeniel.com';
$newPassword = 'admin123';

$user = User::where('email', $email)->first();

if ($user) {
    $user->password = Hash::make($newPassword);
    $user->save();
    echo "✓ Contraseña actualizada exitosamente\n";
} else {
    User::create([
        'name' => 'Administrador',
        'email' => $email,
        'password' => Hash::make($newPassword),
    ]);
    echo "✓ Usuario creado exitosamente\n";
}

echo "\nCredenciales de acceso:\n";
echo "Email: {$email}\n";
echo "Contraseña: {$newPassword}\n";
