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
        Schema::table('poas', function (Blueprint $table) {
            // Agregar columna activo (booleano, por defecto false)
            // Solo puede haber un POA activo a la vez por institución/UE
            $table->boolean('activo')->default(false)->after('anio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poas', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};
