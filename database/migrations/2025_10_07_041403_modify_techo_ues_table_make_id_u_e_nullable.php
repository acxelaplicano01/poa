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
        Schema::table('techo_ues', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea existente
            $table->dropForeign(['idUE']);
            
            // Modificar la columna para permitir null
            $table->unsignedBigInteger('idUE')->nullable()->change();
            
            // Volver a agregar la restricción de clave foránea
            $table->foreign('idUE')->references('id')->on('unidad_ejecutora');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('techo_ues', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea
            $table->dropForeign(['idUE']);
            
            // Modificar la columna para no permitir null
            $table->unsignedBigInteger('idUE')->nullable(false)->change();
            
            // Volver a agregar la restricción de clave foránea
            $table->foreign('idUE')->references('id')->on('unidad_ejecutora');
        });
    }
};
