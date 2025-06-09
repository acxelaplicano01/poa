<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Crear tabla tipo_actividads 
        if (!Schema::hasTable('tipo_actividads')) {
            Schema::create('tipo_actividads', function (Blueprint $table) {
                $table->id();
                $table->string('tipo');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Crear tabla actividades
        if (!Schema::hasTable('actividads')) {
            Schema::create('actividads', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');
                $table->text('descripcion');
                $table->string('correlativo');
                $table->text('resultadoActividad');
                $table->text('poblacion_objetivo');
                $table->text('medio_verificacion');
                $table->enum('estado', ['FORMULACION', 'REFORMULACION', 'REVISION', 'APROBADO', 'RECHAZADO']);
                $table->boolean('finalizada')->default(false);
                $table->boolean('uploadedIntoSPI')->default(false);

                $table->foreignId('idPoa')->constrained('poas');
                $table->foreignId('idPoaDepto')->constrained('poa_deptos');
                $table->foreignId('idInstitucion')->constrained('institucions');
                $table->foreignId('idDepto')->constrained('departamentos'); // 
                $table->foreignId('idUE')->constrained('unidad_ejecutora'); // 
                $table->foreignId('idTipo')->constrained('tipo_actividads');
                $table->foreignId('idResultado')->constrained('resultados');
                $table->unsignedBigInteger('idCategoria')->default(1); 

                $table->timestamp('finalizada_at')->nullable();
                $table->foreignId('finalizada_by')->nullable()->constrained('users')->nullOnDelete();

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Tabla indicadores
        if (!Schema::hasTable('indicadores')) {
            Schema::create('indicadores', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');
                $table->text('descripcion');
                $table->integer('cantidadPlanificada');
                $table->integer('cantidadEjecutada')->nullable();
                $table->double('promedioAlcanzado')->nullable();
                $table->boolean('isCantidad')->default(false);
                $table->boolean('isPorcentaje')->default(false);

                $table->foreignId('idActividad')->constrained('actividads')->onDelete('cascade');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps(); 
                $table->softDeletes(); 
            });
        }

        // Tabla eventos
        if (!Schema::hasTable('eventos')) {
            Schema::create('eventos', function (Blueprint $table) {
                $table->id();
                $table->string('evento');
                $table->enum('tipo', ['REVISION', 'REFORMULACION', 'PUBLICACION', 'APROBADO', 'RECHAZADO', 'SEGUIMIENTO']);
                $table->dateTime('fecha');

                $table->foreignId('idUser')->constrained('users')->onDelete('cascade');
                $table->foreignId('idActividad')->constrained('actividads')->onDelete('cascade');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Tabla revisiones
        if (!Schema::hasTable('revisions')) {
            Schema::create('revisions', function (Blueprint $table) {
                $table->id();
                $table->text('revision');
                $table->enum('tipo', ['TAREA', 'INDICADOR', 'PLANIFICACION']);
                $table->boolean('corregido')->default(false);
                $table->unsignedBigInteger('idForaneo');
                 $table->foreign('idActividad')->references('id')->on('actividads');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Tabla medio_verificacion_actividad
        if (!Schema::hasTable('medio_verificacion_actividad')) {
            Schema::create('medio_verificacion_actividad', function (Blueprint $table) {
                $table->id();
                $table->string('observacion');

                $table->foreign('idArchivo')->references('id')->on('archivos');
                $table->foreign('idActividad')->references('id')->on('actividads');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes();

                
            });
        }

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

    public function down(): void
    {
        Schema::dropIfExists('empleado_actividads');
        Schema::dropIfExists('medio_verificacion_actividad');
        Schema::dropIfExists('revisions');
        Schema::dropIfExists('indicadores');
        Schema::dropIfExists('eventos');
        Schema::dropIfExists('actividads');
        Schema::dropIfExists('tipo_actividads');

    }
};
