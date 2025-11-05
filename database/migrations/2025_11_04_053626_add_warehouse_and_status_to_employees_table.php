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
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->nullable()->after('supervisor_id')->constrained('warehouses')->nullOnDelete();
            $table->enum('status', ['activo', 'inactivo', 'suspendido', 'vacaciones', 'temporal'])->default('activo')->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id', 'status']);
        });
    }
};
