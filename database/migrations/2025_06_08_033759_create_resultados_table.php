<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }
};
