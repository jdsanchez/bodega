<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextileType extends Model
{
    protected $fillable = [
        'name',
        'color',
        'photo',
        'description',
        'material',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Scope para filtrar textiles activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope para filtrar textiles inactivos
     */
    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    /**
     * Accessor para obtener la etiqueta del estado
     */
    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Activo' : 'Inactivo';
    }

    /**
     * Obtener los componentes RGBA del color
     */
    public function getRgbaComponentsAttribute()
    {
        // Extrae los valores RGBA del string: rgba(255, 0, 0, 1)
        preg_match('/rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?\)/', $this->color, $matches);
        
        return [
            'r' => $matches[1] ?? 0,
            'g' => $matches[2] ?? 0,
            'b' => $matches[3] ?? 0,
            'a' => $matches[4] ?? 1,
        ];
    }
}
