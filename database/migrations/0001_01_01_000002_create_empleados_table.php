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
        if (!Schema::hasTable('empleados')) {
            Schema::create('empleados', function (Blueprint $table) {
                $table->id();
                $table->string('dni');
                $table->string('num_empleado');
                $table->string('nombre');
                $table->string('apellido');
                $table->string('direccion')->nullable();
                $table->string('telefono')->nullable();
                $table->date('fechaNacimiento')->nullable(); //antes estaba con un string ¿?
                $table->string('sexo', 1);

                $table->foreignId('idUnidadEjecutora')->constrained('unidad_ejecutora');

            
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->unsignedBigInteger('deleted_by')->nullable();
                
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
       Schema::dropIfExists('empleados');
    }
};
