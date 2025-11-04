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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->text('address');
            $table->string('phone');
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('whatsapp')->nullable();
            $table->text('google_maps_url')->nullable();
            $table->string('photo')->nullable();
            $table->integer('capacity'); // capacidad en metros cuadrados o unidades
            $table->enum('status', ['activa', 'inactiva', 'mantenimiento'])->default('activa');
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
