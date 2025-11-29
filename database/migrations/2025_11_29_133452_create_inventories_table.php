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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            
            // Relaciones con otras tablas
            $table->foreignId('product_type_id')->constrained('product_types')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('textile_type_id')->nullable()->constrained('textile_types')->nullOnDelete();
            
            // Información del producto
            $table->string('name'); // Nombre específico del item en inventario
            $table->decimal('quantity', 10, 2); // Cantidad en stock
            $table->string('unit')->default('unidades'); // unidades, metros, kilogramos, etc.
            $table->decimal('unit_price', 10, 2); // Precio unitario
            $table->decimal('total_price', 10, 2); // Precio total calculado
            
            // Códigos de identificación
            $table->string('barcode')->unique()->nullable(); // Código de barras
            $table->string('sku')->unique(); // Stock Keeping Unit
            
            // Ubicación y estado
            $table->string('location')->nullable(); // Ubicación física en la bodega (ej: "Estante A-3")
            $table->enum('status', ['disponible', 'reservado', 'agotado', 'en_transito', 'dañado'])->default('disponible');
            
            // Información adicional
            $table->text('notes')->nullable(); // Notas o comentarios
            $table->json('photos')->nullable(); // Array de rutas de fotos
            
            // Auditoría - quién hizo qué
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes(); // Para eliminación lógica
            
            // Índices para mejorar búsquedas
            $table->index(['warehouse_id', 'status']);
            $table->index(['product_type_id', 'warehouse_id']);
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
