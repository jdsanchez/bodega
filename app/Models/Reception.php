<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reception extends Model
{
    protected $fillable = [
        'warehouse_id',
        'supplier_id',
        'reception_number',
        'invoice_number',
        'purchase_order',
        'expected_date',
        'reception_date',
        'status',
        'notes',
        'rejection_reason',
        'total_expected',
        'total_received',
        'created_by',
        'received_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'expected_date' => 'date',
        'reception_date' => 'date',
        'approved_at' => 'datetime',
        'total_expected' => 'decimal:2',
        'total_received' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reception) {
            if (auth()->check()) {
                $reception->created_by = auth()->id();
            }
            
            // Generar número de recepción automático
            if (!$reception->reception_number) {
                $reception->reception_number = 'REC-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });

        // Al aprobar, crear inventario automáticamente
        static::updating(function ($reception) {
            if ($reception->isDirty('status') && $reception->status === 'recibida' && !$reception->approved_at) {
                $reception->approved_at = now();
                if (auth()->check()) {
                    $reception->approved_by = auth()->id();
                }
                
                // Crear inventario para cada item
                foreach ($reception->items as $item) {
                    if ($item->received_quantity > 0 && $item->condition === 'bueno') {
                        $inventory = Inventory::create([
                            'product_type_id' => $item->product_type_id,
                            'warehouse_id' => $reception->warehouse_id,
                            'textile_type_id' => $item->textile_type_id,
                            'name' => $item->product_name,
                            'quantity' => $item->received_quantity,
                            'unit' => $item->unit,
                            'unit_price' => $item->unit_price,
                            'barcode' => $item->barcode,
                            'sku' => $item->sku ?? 'SKU-' . strtoupper(uniqid()),
                            'location' => $item->location,
                            'status' => 'disponible',
                            'notes' => "Recibido desde recepción {$reception->reception_number}. " . $item->notes,
                        ]);
                        
                        // Vincular el item con el inventario creado
                        $item->update(['inventory_id' => $inventory->id]);
                    }
                }
            }
        });
    }

    // ============= RELACIONES =============

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReceptionItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ============= SCOPES =============

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'recibida');
    }

    // ============= ACCESSORS =============

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pendiente' => 'Pendiente',
            'en_revision' => 'En Revisión',
            'recibida' => 'Recibida',
            'parcial' => 'Parcial',
            'rechazada' => 'Rechazada',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pendiente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'en_revision' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'recibida' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'parcial' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'rechazada' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTotalExpectedFormattedAttribute(): string
    {
        return '$' . number_format($this->total_expected, 2);
    }

    public function getTotalReceivedFormattedAttribute(): string
    {
        return '$' . number_format($this->total_received, 2);
    }

    public function getCompletionPercentageAttribute(): float
    {
        if ($this->total_expected == 0) {
            return 0;
        }
        return ($this->total_received / $this->total_expected) * 100;
    }

    // ============= MÉTODOS =============

    public function calculateTotals()
    {
        $this->total_expected = $this->items->sum(function ($item) {
            return $item->expected_quantity * $item->unit_price;
        });
        
        $this->total_received = $this->items->sum('total_price');
        
        $this->save();
    }

    public function canBeApproved(): bool
    {
        return in_array($this->status, ['pendiente', 'en_revision', 'parcial']);
    }

    public function canBeRejected(): bool
    {
        return $this->status !== 'rechazada' && $this->status !== 'recibida';
    }
}
