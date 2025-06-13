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
        if (!Schema::hasTable('acta_entrega')) {
            Schema::create('acta_entrega', function (Blueprint $table) {
                $table->id();
                $table->string('correlativo');
                $table->dateTime('fecha_extendida')->default(DB::raw('CURRENT_TIMESTAMP'));

                $table->unsignedBigInteger('idTipoActaEntrega');
                $table->unsignedBigInteger('idRequisicion');
                $table->unsignedBigInteger('idEjecucionPresupuestaria');

                $table->foreign('idTipoActaEntrega')->references('id')->on('tipo_acta_entrega');
                $table->foreign('idRequisicion')->references('id')->on('requisicion');
                $table->foreign('idEjecucionPresupuestaria')->references('id')->on('ejecucion_presupuestaria');

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
        Schema::dropIfExists('acta_entrega');     
    }
};
