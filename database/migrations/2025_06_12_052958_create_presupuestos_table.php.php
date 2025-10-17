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
         if (!Schema::hasTable('presupuestos')) {
            Schema::create('presupuestos', function (Blueprint $table) {
                $table->id();
                $table->decimal('cantidad', 10, 2);
                $table->decimal('costounitario', 10, 2);
                $table->decimal('total', 10, 2);
                
                $table->integer('idgrupo');
                $table->integer('idobjeto');
                $table->foreignId('idtarea')->constrained('tareas')->cascadeOnDelete();
                $table->foreignId('idfuente')->constrained('fuente')->cascadeOnDelete();
                $table->foreignId('idunidad')->constrained('unidadmedidas')->cascadeOnDelete();
                $table->integer('idMes');
                
                $table->text('detalle_tecnico');
                $table->text('recurso');
                $table->integer('idHistorico')->default(1);

                $table->timestamps();
                $table->softDeletes();

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
