<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Crear tabla institucions
        if (!Schema::hasTable('institucions')) {
            Schema::create('institucions', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('descripcion');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Crear tabla peis 
        if (!Schema::hasTable('peis')) {
            Schema::create('peis', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->date('initialYear');
                $table->date('finalYear');
      
                $table->foreignId('idInstitucion')
                      ->constrained('institucions')
                      ->onDelete('cascade');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }

         // Tabla dimensions 
        if (!Schema::hasTable('dimensions')) {
            Schema::create('dimensions', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('descripcion');
                $table->foreignId('idPei')->constrained('peis');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Tabla objetivos

        if (!Schema::hasTable('objetivos')) {
            Schema::create('objetivos', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');
                $table->text('descripcion');
                $table->foreignId('idDimension')->constrained('dimensions');
                $table->foreignId('idPei')->constrained('peis');
                $table->timestamps();
                $table->softDeletes();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
            });
        }
        // Tabla areas

        if (!Schema::hasTable('areas')) {
            Schema::create('areas', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');

                $table->foreignId('idObjetivos')->constrained('objetivos')->onDelete('cascade');
                $table->foreignId('idDimension')->constrained('dimensions')->onDelete('cascade');
                $table->foreignId('idPei')->constrained('peis')->onDelete('cascade');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Tabla resultados

        if (!Schema::hasTable('resultados')) {
            Schema::create('resultados', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');
                $table->text('descripcion');

                $table->foreignId('idArea')->constrained('areas')->onDelete('cascade');
                $table->foreignId('idObjetivos')->constrained('objetivos')->onDelete('cascade');
                $table->foreignId('idDimension')->constrained('dimensions')->onDelete('cascade');
                $table->foreignId('idPei')->constrained('peis')->onDelete('cascade');

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
        Schema::dropIfExists('resultados');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('objetivos');
        Schema::dropIfExists('dimensions');
        Schema::dropIfExists('peis');
        Schema::dropIfExists('institucions');
    }
};
