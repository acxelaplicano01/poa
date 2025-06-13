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
         // Crear tabla actividades
        if (!Schema::hasTable('actividads')) {
            Schema::create('actividads', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');
                $table->text('descripcion');
                $table->string('correlativo');
                $table->text('resultadoActividad');
                $table->text('poblacion_objetivo');
                $table->text('medio_verificacion');
                $table->enum('estado', ['FORMULACION', 'REFORMULACION', 'REVISION', 'APROBADO', 'RECHAZADO']);
                $table->boolean('finalizada')->default(false);
                $table->boolean('uploadedIntoSPI')->default(false);

                $table->foreignId('idPoa')->constrained('poas');
                $table->foreignId('idPoaDepto')->constrained('poa_deptos');
                $table->foreignId('idInstitucion')->constrained('institucions');
                $table->foreignId('idDeptartamento')->constrained('departamentos'); // 
                $table->foreignId('idUE')->constrained('unidad_ejecutora'); // 
                $table->foreignId('idTipo')->constrained('tipo_actividads');
                $table->foreignId('idResultado')->constrained('resultados');
                $table->unsignedBigInteger('idCategoria')->default(1); 

                $table->timestamp('finalizada_at')->nullable();
                $table->foreignId('finalizada_by')->nullable()->constrained('users')->nullOnDelete();

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

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
        Schema::dropIfExists('actividads');
    }
};
