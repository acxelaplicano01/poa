<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla pei_elementos 

        if (!Schema::hasTable('pei_elementos')) {
            Schema::create('pei_elementos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('idPei')->constrained('peis')->onDelete('cascade');
                $table->unsignedBigInteger('elemento_id'); // ID del elemento relacionado
                $table->string('elemento_tipo'); // Nombre de la tabla (objetivos, resultados, areas, dimensiones)
                $table->timestamps();
                $table->softDeletes();

                $table->index(['elemento_id', 'elemento_tipo']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pei_elementos');
    }
};
