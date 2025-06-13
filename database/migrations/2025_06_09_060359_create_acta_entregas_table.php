<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('tipo_acta_entrega')) {
            Schema::create('tipo_acta_entrega', function (Blueprint $table) {
                $table->id();
                $table->string('tipo')->default('');
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });

            DB::table('tipo_acta_entrega')->insert([
                ['tipo' => 'Final', 'created_at' => now(), 'updated_at' => now()],
                ['tipo' => 'Intermedia', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

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

    public function down(): void
    {
        Schema::dropIfExists('detalle_acta_entrega');
        Schema::dropIfExists('acta_entrega');
        Schema::dropIfExists('tipo_acta_entrega');
    }
};
