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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('dpi')->unique();
            $table->string('nit')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('hire_date');
            $table->date('start_date')->nullable();
            $table->enum('role', [
                'bodeguero',
                'secretaria',
                'asistente',
                'piloto',
                'repartidor',
                'encargado_bodega',
                'jefe',
                'administrador',
                'gerente'
            ]);
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
