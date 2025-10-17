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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimiento_tareas');      
    }
};
