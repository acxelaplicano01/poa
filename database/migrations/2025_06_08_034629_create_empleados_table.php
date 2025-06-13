<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

                 $table->foreignId('user_id')->nullable()->constrained('users');
                $table->foreignId('idUnidadEjecutora')->constrained('unidad_ejecutora');

            
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Crear tabla empleado_deptos
        if (!Schema::hasTable('empleado_deptos')) {
            Schema::create('empleado_deptos', function (Blueprint $table) {
                $table->id(); 
                $table->foreignId('idEmpleado')->constrained('empleados');
                $table->foreignId('idDepto')->constrained('departamentos');
                $table->timestamps();
                $table->softDeletes();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('empleado_deptos');
        Schema::dropIfExists('empleados');
    }
};
