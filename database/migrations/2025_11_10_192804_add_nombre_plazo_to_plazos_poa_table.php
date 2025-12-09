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
        Schema::table('plazos_poa', function (Blueprint $table) {
            // Agregar campo para nombre personalizado de plazo (opcional)
            $table->string('nombre_plazo', 255)->nullable()->after('tipo_plazo');
            
            // Eliminar el constraint único que impedía múltiples plazos del mismo tipo
            $table->dropUnique('unique_plazo_activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plazos_poa', function (Blueprint $table) {
            $table->dropColumn('nombre_plazo');
            
            // Restaurar el constraint único
            $table->unique(['idPoa', 'tipo_plazo', 'activo'], 'unique_plazo_activo');
        });
    }
};
