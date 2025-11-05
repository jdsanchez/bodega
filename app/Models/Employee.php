<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'dpi',
        'nit',
        'phone',
        'mobile',
        'address',
        'birth_date',
        'hire_date',
        'start_date',
        'role',
        'supervisor_id',
        'warehouse_id',
        'photo',
        'status',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'start_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relación con el supervisor (jefe directo)
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    // Relación con la bodega
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    // Relación con los empleados supervisados
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    // Relación con las asistencias
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // Accessor para nombre completo
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Accessor para el nombre del rol en español
    public function getRoleNameAttribute(): string
    {
        $roles = [
            'bodeguero' => 'Bodeguero',
            'secretaria' => 'Secretaria',
            'asistente' => 'Asistente',
            'piloto' => 'Piloto',
            'repartidor' => 'Repartidor',
            'encargado_bodega' => 'Encargado de Bodega',
            'jefe' => 'Jefe',
            'administrador' => 'Administrador',
            'gerente' => 'Gerente',
        ];

        return $roles[$this->role] ?? $this->role;
    }

    // Obtener todos los roles disponibles
    public static function getRoles(): array
    {
        return [
            'bodeguero' => 'Bodeguero',
            'secretaria' => 'Secretaria',
            'asistente' => 'Asistente',
            'piloto' => 'Piloto',
            'repartidor' => 'Repartidor',
            'encargado_bodega' => 'Encargado de Bodega',
            'jefe' => 'Jefe',
            'administrador' => 'Administrador',
            'gerente' => 'Gerente',
        ];
    }

    // Accessor para el nombre del estado en español
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
            'suspendido' => 'Suspendido',
            'vacaciones' => 'Vacaciones',
            'temporal' => 'Temporal',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // Accessor para el color del estado
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'activo' => 'green',
            'inactivo' => 'gray',
            'suspendido' => 'red',
            'vacaciones' => 'blue',
            'temporal' => 'yellow',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    // Obtener todos los estados disponibles
    public static function getStatuses(): array
    {
        return [
            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
            'suspendido' => 'Suspendido',
            'vacaciones' => 'Vacaciones',
            'temporal' => 'Temporal',
        ];
    }
}
