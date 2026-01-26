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
        // Tabla Ejecucion_Presupuestaria_Logs
        if (!Schema::hasTable('ejecucion_presupuestaria_logs')) {
            Schema::create('ejecucion_presupuestaria_logs', function (Blueprint $table) {
                $table->id();
                $table->string('observacion');
                $table->string('log');

                $table->foreignId('idEjecucionPresupuestaria')->constrained('ejecucion_presupuestaria');
                
                // Auditoría
                
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
        Schema::dropIfExists('ejecucion_presupuestaria_logs');
    }
};
