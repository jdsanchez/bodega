<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    protected $fillable = [
        'module_key',
        'module_name',
        'module_icon',
        'super_admin',
        'admin',
        'gerente_bodega',
        'supervisor',
        'empleado',
        'order'
    ];

    protected $casts = [
        'super_admin' => 'boolean',
        'admin' => 'boolean',
        'gerente_bodega' => 'boolean',
        'supervisor' => 'boolean',
        'empleado' => 'boolean',
    ];

    /**
     * Verificar si un rol tiene acceso al módulo
     */
    public function hasAccess(string $role): bool
    {
        return $this->$role ?? false;
    }

    /**
     * Obtener roles con acceso
     */
    public function getRolesWithAccess(): array
    {
        $roles = [];
        $roleColumns = ['super_admin', 'admin', 'gerente_bodega', 'supervisor', 'empleado'];
        
        foreach ($roleColumns as $role) {
            if ($this->$role) {
                $roles[] = $role;
            }
        }
        
        return $roles;
    }
}
