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
        Schema::table('procesos_compras', function (Blueprint $table) {
            $table->foreign('idTipoProcesoCompra')
                  ->references('id')
                  ->on('tipo_proceso_compra')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procesos_compras', function (Blueprint $table) {
            $table->dropForeign(['idTipoProcesoCompra']);
        });
    }
};
