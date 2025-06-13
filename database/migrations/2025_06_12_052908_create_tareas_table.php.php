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
         // Crear tabla tareas
         if (!Schema::hasTable('tareas')) {
            Schema::create('tareas', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');
                $table->text('descripcion');
                $table->text('correlativo');
                $table->enum('estado', ['REVISION', 'APROBADO', 'RECHAZADO']);
                $table->boolean('isPresupuesto')->default(false);
                
                $table->foreignId('idActividad')->constrained('actividads')->cascadeOnDelete();
                $table->foreignId('idPoa')->constrained('poas')->cascadeOnDelete();
                $table->foreignId('idDeptartamento')->constrained('departamentos')->cascadeOnDelete();
                $table->foreignId('idUE')->constrained('unidad_ejecutora')->cascadeOnDelete();

                $table->timestamps();
                $table->softDeletes();

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
