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
        Schema::create('textile_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color'); // RGBA format: rgba(255,0,0,1)
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->string('material');
            $table->boolean('status')->default(true); // true = activo, false = inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('textile_types');
    }
};
