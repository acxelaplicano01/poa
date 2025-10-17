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
         if (!Schema::hasTable('detalle_ejecucion_presupuestaria')) {
            Schema::create('detalle_ejecucion_presupuestaria', function (Blueprint $table) {
                $table->id();

                $table->text('observacion')->nullable();
                $table->text('referenciaActaEntrega')->nullable();

                $table->decimal('cant_ejecutada', 10, 2);
                $table->decimal('monto_unitario_ejecutado', 10, 2);
                $table->decimal('monto_total_ejecutado', 10, 2);

                $table->dateTime('fechaEjecucion')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));

                // Relaciones foráneas
                $table->foreignId('idPresupuesto')->constrained('presupuestos');
                $table->foreignId('idDetalleRequisicion')->constrained('detalle_requisicion');
                $table->foreignId('idEjecucion')->constrained('ejecucion_presupuestaria');

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
        Schema::dropIfExists('detalle_ejecucion_presupuestaria');
    }
};
