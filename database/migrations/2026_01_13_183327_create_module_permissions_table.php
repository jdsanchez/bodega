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
        Schema::create('module_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module_key')->unique();
            $table->string('module_name');
            $table->text('module_icon')->nullable();
            $table->boolean('super_admin')->default(true);
            $table->boolean('admin')->default(false);
            $table->boolean('gerente_bodega')->default(false);
            $table->boolean('supervisor')->default(false);
            $table->boolean('empleado')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_permissions');
    }
};
