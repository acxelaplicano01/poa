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
        if (!Schema::hasTable('poa_deptos')) {
            Schema::create('poa_deptos', function (Blueprint $table) {
                $table->id();
                $table->boolean('isActive')->default(true);
                $table->foreignId('idPoa')->constrained('poas');
                $table->foreignId('idDepartamento')->constrained('departamentos');
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
                
                // Índice único para evitar duplicados
                $table->unique(['idPoa', 'idDepartamento'], 'unique_poa_departamento');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poa_deptos');
    }
};
