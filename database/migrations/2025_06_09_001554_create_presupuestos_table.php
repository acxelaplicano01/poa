<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabla trimestres
        if (!Schema::hasTable('trimestre')) {
            Schema::create('trimestre', function (Blueprint $table) {
                $table->id();
                $table->string('trimestre');
                $table->timestamps();
                $table->softDeletes();

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }

        // Tabla mes
        if (!Schema::hasTable('mes')) {
            Schema::create('mes', function (Blueprint $table) {
                $table->id();
                $table->string('mes');
                $table->foreignId('idTrimestre')->constrained('trimestre')->cascadeOnDelete();
                $table->timestamps();
                $table->softDeletes();

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    
     
        if (!Schema::hasTable('presupuestos')) {
            Schema::create('presupuestos', function (Blueprint $table) {
                $table->integer('idP')->primary();
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

    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
        Schema::dropIfExists('mes');
        Schema::dropIfExists('trimestre');
       
    }
};
