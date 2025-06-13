<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla grupogastos
        if (!Schema::hasTable('grupogastos')) {
            Schema::create('grupogastos', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');
                $table->integer('identificador');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Tabla cuentas_mayores
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

        // Tabla objetogastos

        if (!Schema::hasTable('objetogastos')) {
            Schema::create('objetogastos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->longText('descripcion');
                $table->string('identificador');

                $table->foreignId('idgrupo')->constrained('grupogastos');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });
        }
        // Tabla fuentes
        if (!Schema::hasTable('fuente')) {
            Schema::create('fuente', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('identificador');

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
        Schema::dropIfExists('fuente');
        Schema::dropIfExists('objetogastos');
        Schema::dropIfExists('cuentas_mayors');
        Schema::dropIfExists('grupogastos');
    }
};
