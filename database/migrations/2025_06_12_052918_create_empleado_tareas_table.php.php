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
        // Crear tabla empleado_tareas
         if (!Schema::hasTable('empleado_tareas')) {
            Schema::create('empleado_tareas', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('idEmpleado');
                $table->unsignedBigInteger('idActividad');
                $table->unsignedBigInteger('idTarea');

                $table->foreign('idEmpleado')->references('id')->on('empleados');
                $table->foreign('idActividad')->references('id')->on('actividads');
                $table->foreign('idTarea')->references('id')->on('tareas');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado_tareas');
    }
};
