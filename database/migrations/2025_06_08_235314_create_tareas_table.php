<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tareas_historicos')) {
            Schema::create('tareas_historicos', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');

                $table->unsignedBigInteger('idobjeto');
                $table->unsignedBigInteger('idunidad');
                $table->unsignedBigInteger('idProcesoCompra');
                $table->unsignedBigInteger('idCubs');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Crear tabla tareas
         if (!Schema::hasTable('tareas')) {
            Schema::create('tareas', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');
                $table->text('descripcion');
                $table->text('correlativo');
                $table->enum('estado', ['REVISION', 'APROBADO', 'RECHAZADO']);
                $table->boolean('isPresupuesto')->default(false);
                
                $table->foreignId('idActividad')->constrained('actividades')->cascadeOnDelete();
                $table->foreignId('idPoa')->constrained('poas')->cascadeOnDelete();
                $table->foreignId('idDepto')->constrained('departamentos')->cascadeOnDelete();
                $table->foreignId('idUE')->constrained('unidad_ejecutoras')->cascadeOnDelete();

                $table->timestamps();
                $table->softDeletes();

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }

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

    public function down(): void
    {
        Schema::dropIfExists('empleado_tareas');
        Schema::dropIfExists('tareas_historicos');
        Schema::dropIfExists('tareas');
    }
};
