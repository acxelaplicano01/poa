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
        Schema::create('plazos_poa', function (Blueprint $table) {
            $table->id();
            
            // Tipo de plazo
            $table->enum('tipo_plazo', [
                'asignacion_nacional',      // Plazo para asignación presupuestaria nacional
                'asignacion_departamental', // Plazo para asignación a departamentos
                'planificacion',            // Plazo para planificar actividades
                'requerimientos',           // Plazo para crear requerimientos
                'seguimiento'               // Plazo para seguimiento
            ]);
            
            // Fechas del plazo
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            
            // Relación con POA
            $table->foreignId('idPoa')->constrained('poas')->onDelete('cascade');
            
            // Estado del plazo
            $table->boolean('activo')->default(true);
            
            // Descripción opcional
            $table->text('descripcion')->nullable();
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para búsquedas rápidas
            $table->index(['idPoa', 'tipo_plazo', 'activo']);
            $table->index(['fecha_inicio', 'fecha_fin']);
            
            // Constraint: no puede haber dos plazos del mismo tipo activos a la vez para el mismo POA
            $table->unique(['idPoa', 'tipo_plazo', 'activo'], 'unique_plazo_activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plazos_poa');
    }
};
