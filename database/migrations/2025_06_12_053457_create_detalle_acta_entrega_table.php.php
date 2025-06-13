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
        if (!Schema::hasTable('detalle_acta_entrega')) {
            Schema::create('detalle_acta_entrega', function (Blueprint $table) {
                $table->id();
                $table->decimal('log_cant_ejecutada', 10, 2);
                $table->decimal('log_monto_unitario_ejecutado', 10, 2);
                $table->dateTime('log_fechaEjecucion')->default(DB::raw('CURRENT_TIMESTAMP'));

                $table->unsignedBigInteger('idActaEntrega');
                $table->unsignedBigInteger('idRequisicion');
                $table->unsignedBigInteger('idDetalleRequisicion');
                $table->unsignedBigInteger('idEjecucionPresupuestaria');
                $table->unsignedBigInteger('idDetalleEjecucionPresupuestaria');

                $table->foreign('idActaEntrega')->references('id')->on('acta_entrega');
                $table->foreign('idRequisicion')->references('id')->on('requisicion');
                $table->foreign('idDetalleRequisicion')->references('id')->on('detalle_requisicion');
                $table->foreign('idEjecucionPresupuestaria')->references('id')->on('ejecucion_presupuestaria');
                $table->foreign('idDetalleEjecucionPresupuestaria')->references('id')->on('detalle_ejecucion_presupuestaria');

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
        Schema::dropIfExists('detalle_acta_entrega');
    }
};
