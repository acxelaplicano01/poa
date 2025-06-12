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
        
        // Tabla indicadores
        if (!Schema::hasTable('indicadores')) {
            Schema::create('indicadores', function (Blueprint $table) {
                $table->id();
                $table->text('nombre');
                $table->text('descripcion');
                $table->integer('cantidadPlanificada');
                $table->integer('cantidadEjecutada')->nullable();
                $table->double('promedioAlcanzado')->nullable();
                $table->boolean('isCantidad')->default(false);
                $table->boolean('isPorcentaje')->default(false);

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
        Schema::dropIfExists('indicadores');
    }
};
