<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('detalle_requisicion')) {
            Schema::create('detalle_requisicion', function (Blueprint $table) {
                $table->id();
                $table->text('referenciaActaEntrega')->nullable();
                $table->integer('cantidad');
                $table->boolean('entregado')->default(false);
                
                $table->foreignId('idRequisicion')->constrained('requisicion');
                $table->foreignId('idPoa')->constrained('poas');
                $table->foreignId('idPresupuesto')->constrained('presupuestos', 'idP');
                $table->foreignId('idRecurso')->constrained('tareas_historicos');
                $table->foreignId('idUnidadMedida')->constrained('unidadmedidas');

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
        Schema::dropIfExists('detalle_requisicion');
    }
};
