<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        // Crear tabla deptos (antes estaba como deptos)
        if (!Schema::hasTable('departamentos')) {
            Schema::create('departamentos', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('siglas');
                $table->string('estructura');
                $table->enum('tipo', [
                    'ADMINISTRATIVO',
                    'COORDINACIÓN ACADÉMICA',
                    'COORDINACIÓN REGIONAL',
                    'DEPARTAMENTO ACADÉMICO',
                    'SECCIÓN ACADÉMICA',
                    'SERVICIOS'
                ]);
                $table->foreignId('idUnidadEjecutora')
                      ->constrained('unidad_ejecutora')
                      ->onDelete('cascade');

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
        Schema::dropIfExists('departamentos');
    }
};
