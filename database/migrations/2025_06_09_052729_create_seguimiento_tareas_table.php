<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('seguimiento_tareas')) {
            Schema::create('seguimiento_tareas', function (Blueprint $table) {
                $table->id();
                $table->string('seguimiento')->nullable();
                $table->decimal('monto_ejecutado', 10, 0);
                $table->dateTime('fecha');

                $table->unsignedBigInteger('idTarea');
                $table->unsignedBigInteger('idActividad');
                $table->unsignedBigInteger('idPoaDepto');
                $table->unsignedBigInteger('idPresupuesto');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('medio_verificacions')) {
            Schema::create('medio_verificacions', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');
                $table->text('descripcion');
                $table->text('url')->nullable();
                $table->text('nombre_Archivo')->nullable();

                table->foreign('idSeguimiento')->references('id')->on('seguimiento_tareas');

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
        Schema::dropIfExists('medio_verificacions');
        Schema::dropIfExists('seguimiento_tareas');
    }
};
