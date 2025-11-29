<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('receptions', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            
            // Información de la recepción
            $table->string('reception_number')->unique(); // Número de recepción interno
            $table->string('invoice_number')->nullable(); // Número de factura del proveedor
            $table->string('purchase_order')->nullable(); // Número de orden de compra
            
            // Fechas
            $table->date('expected_date'); // Fecha esperada de llegada
            $table->date('reception_date')->nullable(); // Fecha real de recepción
            
            // Estado y condiciones
            $table->enum('status', ['pendiente', 'en_revision', 'recibida', 'parcial', 'rechazada'])->default('pendiente');
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Totales
            $table->decimal('total_expected', 12, 2)->default(0);
            $table->decimal('total_received', 12, 2)->default(0);
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete(); // Quien recibió físicamente
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // Quien aprobó la recepción
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['warehouse_id', 'status']);
            $table->index('reception_date');
            $table->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receptions');
    }
};
