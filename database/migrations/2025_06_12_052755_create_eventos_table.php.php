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
            // Tabla eventos
        if (!Schema::hasTable('eventos')) {
            Schema::create('eventos', function (Blueprint $table) {
                $table->id();
                $table->string('evento');
                $table->enum('tipo', ['REVISION', 'REFORMULACION', 'PUBLICACION', 'APROBADO', 'RECHAZADO', 'SEGUIMIENTO']);
                $table->dateTime('fecha');

                $table->foreignId('idUser')->constrained('users')->onDelete('cascade');
                $table->foreignId('idActividad')->constrained('actividads')->onDelete('cascade');

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
        Schema::dropIfExists('eventos');
    }
};
