<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_type_id',
        'warehouse_id',
        'textile_type_id',
        'name',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
        'barcode',
        'sku',
        'location',
        'status',
        'notes',
        'photos',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'photos' => 'array',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot del modelo para manejar eventos
     */
    protected static function boot()
    {
        parent::boot();

        // Al crear un registro
        static::creating(function ($inventory) {
            if (auth()->check()) {
                $inventory->created_by = auth()->id();
            }
            // Calcular precio total automáticamente
            $inventory->total_price = $inventory->quantity * $inventory->unit_price;
        });

        // Al actualizar un registro
        static::updating(function ($inventory) {
            if (auth()->check()) {
                $inventory->updated_by = auth()->id();
            }
            // Recalcular precio total si cambió cantidad o precio unitario
            if ($inventory->isDirty(['quantity', 'unit_price'])) {
                $inventory->total_price = $inventory->quantity * $inventory->unit_price;
            }
            
            // Actualizar estado a agotado si la cantidad es 0
            if ($inventory->quantity <= 0 && $inventory->status !== 'dañado') {
                $inventory->status = 'agotado';
            }
        });

        // Al eliminar (soft delete)
        static::deleting(function ($inventory) {
            if (auth()->check() && !$inventory->isForceDeleting()) {
                $inventory->deleted_by = auth()->id();
                $inventory->save();
            }
        });
    }

    // ============= RELACIONES =============

    /**
     * Relación con el tipo de producto
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Relación con la bodega
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Relación con el tipo de textil (opcional)
     */
    public function textileType(): BelongsTo
    {
        return $this->belongsTo(TextileType::class);
    }

    /**
     * Usuario que creó el registro
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuario que actualizó el registro
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Usuario que eliminó el registro
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // ============= SCOPES =============

    /**
     * Scope para filtrar por bodega
     */
    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para productos disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'disponible')->where('quantity', '>', 0);
    }

    /**
     * Scope para productos con stock bajo
     */
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('quantity', '<=', $threshold)->where('quantity', '>', 0);
    }

    /**
     * Scope para productos agotados
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('status', 'agotado')->orWhere('quantity', 0);
    }

    /**
     * Scope para búsqueda general
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('barcode', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
        });
    }

    // ============= ACCESSORS =============

    /**
     * Obtener etiqueta del estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'disponible' => 'Disponible',
            'reservado' => 'Reservado',
            'agotado' => 'Agotado',
            'en_transito' => 'En Tránsito',
            'dañado' => 'Dañado',
            default => $this->status,
        };
    }

    /**
     * Obtener clase CSS según el estado
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'disponible' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'reservado' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'agotado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'en_transito' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'dañado' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Verificar si el stock es bajo
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity > 0 && $this->quantity <= 10;
    }

    /**
     * Obtener valor total formateado
     */
    public function getTotalPriceFormattedAttribute(): string
    {
        return '$' . number_format($this->total_price, 2);
    }

    /**
     * Obtener precio unitario formateado
     */
    public function getUnitPriceFormattedAttribute(): string
    {
        return '$' . number_format($this->unit_price, 2);
    }
}
