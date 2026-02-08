<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
          if (!Schema::hasTable('cuentas_mayors')) {
            Schema::create('cuentas_mayores', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->text('descripcion');
                $table->string('identificador');

                $table->foreignId('idGrupo')
                      ->constrained('grupogastos')
                      ->onDelete('cascade');

                // Auditoría
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        // No hacer nada, se elimina en la migración de grupogastos
    }
};
