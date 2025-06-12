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
         // Tabla medio_verificacion_actividad
        if (!Schema::hasTable('medio_verificacion_actividad')) {        
            Schema::create('medio_verificacion_actividad', function (Blueprint $table) {
                $table->id();
                $table->string('observacion');

                $table->foreignId('idArchivo')->constrained('archivos');
                $table->foreignId('idActividad')->constrained('actividads');

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
        Schema::dropIfExists('medio_verificacion_actividad');
    }
};
