<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Verificar si el usuario tiene alguno de los roles especificados
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Verificar si es super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Verificar si es admin o super admin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Verificar si puede gestionar inventario
     */
    public function canManageInventory(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'gerente_bodega']);
    }

    /**
     * Verificar si solo puede ver (sin editar)
     */
    public function isViewOnly(): bool
    {
        return in_array($this->role, ['supervisor', 'empleado']);
    }

    /**
     * Verificar si puede acceder a un módulo específico
     */
    public function canAccessModule(string $moduleKey): bool
    {
        // Intentar obtener el permiso de la base de datos
        $permission = \App\Models\ModulePermission::where('module_key', $moduleKey)->first();
        
        if ($permission) {
            return $permission->hasAccess($this->role);
        }
        
        // Fallback al sistema antiguo si no existe en la tabla
        return false;
    }

    /**
     * Obtener nombre legible del rol
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Administrador',
            'admin' => 'Administrador',
            'gerente_bodega' => 'Gerente de Bodega',
            'supervisor' => 'Supervisor',
            'empleado' => 'Empleado',
            default => 'Sin Rol',
        };
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
