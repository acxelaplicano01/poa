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
          if (!Schema::hasTable('techo_deptos')) {
            Schema::create('techo_deptos', function (Blueprint $table) {
                $table->id();
                $table->decimal('monto', 10, 2);
                $table->foreignId('idUE')->constrained('unidad_ejecutora');
                $table->foreignId('idPoa')->constrained('poas');
                $table->foreignId('idDepartamento')->constrained('departamentos');
                $table->foreignId('idPoaDepto')->constrained('poa_deptos');
                $table->foreignId('idTechoUE')->constrained('techo_ues');
                $table->unsignedBigInteger('idGrupo')->nullable(); 
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
        Schema::dropIfExists('techo_deptos');
    }
};
