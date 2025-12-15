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
         if (!Schema::hasTable('tareas_historicos')) {
            Schema::create('tareas_historicos', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');

                $table->unsignedBigInteger('idobjeto')->constrained('objetogastos') ->onDelete('cascade');;
                $table->unsignedBigInteger('idunidad')->constrained('unidadmedidas') ->onDelete('cascade');;
                $table->unsignedBigInteger('idProcesoCompra') ->constrained('procesos_compras') ->onDelete('cascade');
                $table->unsignedBigInteger('idCubs') ->constrained('cubs')->onDelete('cascade');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

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
        Schema::dropIfExists('tareas_historicos');
       
    }
};
