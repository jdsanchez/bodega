<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'nit',
        'phone',
        'email',
        'address',
        'status',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
    ];

    // Scopes para filtrar por estado
    public function scopeActive($query)
    {
        return $query->where('status', 'activo');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactivo');
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('status', '!=', 'eliminado');
    }

    // Accessor para el nombre del estado
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
            'eliminado' => 'Eliminado',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // Obtener todos los estados disponibles
    public static function getStatuses(): array
    {
        return [
            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
        ];
    }
}
