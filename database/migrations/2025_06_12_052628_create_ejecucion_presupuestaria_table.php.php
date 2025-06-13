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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ejecucion_presupuestaria');
    }
};
