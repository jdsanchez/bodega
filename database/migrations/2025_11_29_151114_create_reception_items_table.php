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
        Schema::create('reception_items', function (Blueprint $table) {
            $table->id();
            
            // Relación con la recepción
            $table->foreignId('reception_id')->constrained('receptions')->cascadeOnDelete();
            
            // Relaciones de producto
            $table->foreignId('product_type_id')->constrained('product_types')->cascadeOnDelete();
            $table->foreignId('textile_type_id')->nullable()->constrained('textile_types')->nullOnDelete();
            
            // Información del producto
            $table->string('product_name'); // Nombre del producto recibido
            $table->string('sku')->nullable(); // SKU si ya existe
            $table->string('barcode')->nullable();
            
            // Cantidades
            $table->decimal('expected_quantity', 10, 2); // Cantidad esperada según orden
            $table->decimal('received_quantity', 10, 2)->default(0); // Cantidad realmente recibida
            $table->string('unit')->default('unidades'); // unidades, metros, kilogramos, etc.
            
            // Precios
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2); // Se calcula: received_quantity * unit_price
            
            // Condición del producto
            $table->enum('condition', ['bueno', 'dañado', 'defectuoso', 'incompleto'])->default('bueno');
            $table->string('location')->nullable(); // Ubicación donde se almacenó
            
            // Notas específicas del item
            $table->text('notes')->nullable();
            
            // Referencia al inventario creado (si se aprobó)
            $table->foreignId('inventory_id')->nullable()->constrained('inventories')->nullOnDelete();
            
            $table->timestamps();
            
            // Índices
            $table->index('reception_id');
            $table->index('product_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reception_items');
    }
};
