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
         // Crear tabla empleado_deptos
        if (!Schema::hasTable('empleado_deptos')) {
            Schema::create('empleado_deptos', function (Blueprint $table) {
                $table->id(); 
                $table->foreignId('idEmpleado')->constrained('empleados');
                $table->foreignId('idDepto')->constrained('departamentos');
                $table->timestamps();
                $table->softDeletes();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado_deptos');
    }
};
