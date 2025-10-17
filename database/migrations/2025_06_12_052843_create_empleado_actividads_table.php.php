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
         // Tabla empleado_actividads

        if (!Schema::hasTable('empleado_actividads')) {
            Schema::create('empleado_actividads', function (Blueprint $table) {
                $table->id();

                $table->text('descripcion')->nullable();

                $table->unsignedBigInteger('idEmpleado');
                $table->unsignedBigInteger('idActividad');

                $table->foreign('idEmpleado')->references('id')->on('empleados');
                $table->foreign('idActividad')->references('id')->on('actividads');

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
        Schema::dropIfExists('empleado_actividads');
    }
};
