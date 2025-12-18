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
        // Tabla revisiones
        if (!Schema::hasTable('revisions')) {
            Schema::create('revisions', function (Blueprint $table) {
                $table->id();
                $table->text('revision');
                $table->enum('tipo', ['TAREA', 'INDICADOR', 'PLANIFICACION', 'REVISION', 'DICTAMEN']);
                $table->boolean('corregido')->default(false);
                
                $table->unsignedBigInteger('idElemento')->nullable();

                $table->foreignId('idActividad')->constrained('actividads');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

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
        Schema::dropIfExists('revisions');
    }
};
