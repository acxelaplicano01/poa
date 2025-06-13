<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('orden_combustible')) {
            Schema::create('orden_combustible', function (Blueprint $table) {
                $table->id();

                $table->string('correlativo');
                $table->decimal('monto', 10, 2);
                $table->string('monto_en_letras');
                $table->string('modelo_vehiculo');
                $table->string('lugar_salida');
                $table->string('lugar_destino');
                $table->string('placa');
                $table->decimal('recorrido_km', 10, 2);
                $table->dateTime('fecha_actividad');
                $table->string('actividades_realizar');

                $table->unsignedBigInteger('idPoa');
                $table->unsignedBigInteger('idDetalleRequisicion');
                $table->unsignedBigInteger('idRecurso');
                $table->unsignedBigInteger('responsable')->nullable();

                $table->foreign('idPoa')->references('id')->on('poas');
                $table->foreign('idDetalleRequisicion')->references('id')->on('detalle_requisicion');
                $table->foreign('idRecurso')->references('id')->on('tareas_historicos');
                $table->foreign('responsable')->references('id')->on('empleados');

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
        Schema::dropIfExists('orden_combustible');
    }
};
