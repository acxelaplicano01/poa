<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Crear tabla procesos_compras

        if (!Schema::hasTable('procesos_compras')) {
            Schema::create('procesos_compras', function (Blueprint $table) {
                $table->id();
                $table->string('nombre_proceso');
                $table->foreignId('idUE')->constrained('unidad_ejecutoras');
                $table->timestamps();
                $table->softDeletes();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
            });

         
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('procesos_compras');
    }
};
