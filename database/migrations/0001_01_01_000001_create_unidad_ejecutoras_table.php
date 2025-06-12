<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // Crear tabla unidad_ejecutoras primero
        if (!Schema::hasTable('unidad_ejecutora')) {
            Schema::create('unidad_ejecutora', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('descripcion');
                $table->string('estructura');

                $table->foreignId('idInstitucion')->constrained('institucions')->onDelete('cascade');

                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->unsignedBigInteger('deleted_by')->nullable();
                
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('institucions');
        Schema::dropIfExists('unidad_ejecutora');
        
    }
};
