<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceptionItem extends Model
{
    protected $fillable = [
        'reception_id',
        'product_type_id',
        'textile_type_id',
        'product_name',
        'sku',
        'barcode',
        'expected_quantity',
        'received_quantity',
        'unit',
        'unit_price',
        'total_price',
        'condition',
        'location',
        'notes',
        'inventory_id',
    ];

    protected $casts = [
        'expected_quantity' => 'decimal:2',
        'received_quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        // Calcular precio total automáticamente
        static::saving(function ($item) {
            $item->total_price = $item->received_quantity * $item->unit_price;
        });

        // Actualizar totales de la recepción cuando cambie un item
        static::saved(function ($item) {
            $item->reception->calculateTotals();
        });

        static::deleted(function ($item) {
            $item->reception->calculateTotals();
        });
    }

    // ============= RELACIONES =============

    public function reception(): BelongsTo
    {
        return $this->belongsTo(Reception::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function textileType(): BelongsTo
    {
        return $this->belongsTo(TextileType::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    // ============= ACCESSORS =============

    public function getConditionLabelAttribute(): string
    {
        return match($this->condition) {
            'bueno' => 'Bueno',
            'dañado' => 'Dañado',
            'defectuoso' => 'Defectuoso',
            'incompleto' => 'Incompleto',
            default => $this->condition,
        };
    }

    public function getConditionColorAttribute(): string
    {
        return match($this->condition) {
            'bueno' => 'bg-green-100 text-green-800',
            'dañado' => 'bg-red-100 text-red-800',
            'defectuoso' => 'bg-orange-100 text-orange-800',
            'incompleto' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTotalPriceFormattedAttribute(): string
    {
        return '$' . number_format($this->total_price, 2);
    }

    public function getUnitPriceFormattedAttribute(): string
    {
        return '$' . number_format($this->unit_price, 2);
    }

    public function getQuantityDifferenceAttribute(): float
    {
        return $this->received_quantity - $this->expected_quantity;
    }

    public function getIsCompleteAttribute(): bool
    {
        return $this->received_quantity >= $this->expected_quantity;
    }
}
