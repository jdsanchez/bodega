<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'title',
        'address',
        'phone',
        'manager_id',
        'whatsapp',
        'google_maps_url',
        'photo',
        'capacity',
        'status',
        'email',
    ];

    /**
     * Relación con el empleado encargado
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Scope para filtrar bodegas activas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'activa');
    }

    /**
     * Scope para filtrar bodegas inactivas
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactiva');
    }

    /**
     * Scope para filtrar bodegas en mantenimiento
     */
    public function scopeMaintenance($query)
    {
        return $query->where('status', 'mantenimiento');
    }

    /**
     * Accessor para obtener la etiqueta del estado
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'activa' => 'Activa',
            'inactiva' => 'Inactiva',
            'mantenimiento' => 'En Mantenimiento',
            default => $this->status,
        };
    }

    /**
     * Accessor para obtener el color del badge según el estado
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'activa' => 'green',
            'inactiva' => 'gray',
            'mantenimiento' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Obtener todos los estados disponibles
     */
    public static function getStatuses()
    {
        return [
            'activa' => 'Activa',
            'inactiva' => 'Inactiva',
            'mantenimiento' => 'En Mantenimiento',
        ];
    }
}
