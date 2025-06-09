<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla Ejecucion_Presupuestaria
        if (!Schema::hasTable('ejecucion_presupuestaria')) {
            Schema::create('ejecucion_presupuestaria', function (Blueprint $table) {
                $table->id();
                $table->text('observacion')->nullable();
                $table->dateTime('fechaInicioEjecucion')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->dateTime('fechaFinEjecucion')->nullable();

                $table->foreignId('idRequisicion')->constrained('Requisicion');
                $table->foreignId('idEstadoEjecucion')->constrained('estado_ejecucion_presupuestaria');

                // Auditoría
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Tabla Ejecucion_Presupuestaria_Logs
        if (!Schema::hasTable('ejecucion_presupuestaria_logs')) {
            Schema::create('ejecucion_presupuestaria_logs', function (Blueprint $table) {
                $table->id();
                $table->string('observacion');
                $table->string('log');

                $table->foreignId('idEjecucionPresupuestaria')->constrained('ejecucion_presupuestaria');

                $table->timestamps();
                $table->softDeletes(); 
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ejecucion_presupuestaria_logs');
        Schema::dropIfExists('ejecucion_presupuestaria');
    }
};
