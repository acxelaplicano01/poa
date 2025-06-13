<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('planificacions')) {
            Schema::create('planificacions', function (Blueprint $table) {
                $table->id();
                $table->double('cantidad');
                $table->date('fechaInicio');
                $table->date('fechaFin');

                $table->unsignedBigInteger('idActividad');
                $table->unsignedBigInteger('idIndicador');
                $table->unsignedBigInteger('idMes');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes();
            });
        }

         if (!Schema::hasTable('seguimiento_planificacions')) {
            Schema::create('seguimiento_planificacions', function (Blueprint $table) {
                $table->id();
                $table->string('seguimiento')->nullable();
                $table->string('ejecutado')->nullable();
                $table->dateTime('fecha')->nullable();

                $table->foreignId('idPlanificacion')->constrained('planificacions');
                $table->foreignId('idActividad')->constrained('actividads');
                $table->foreignId('idPoaDepto')->constrained('poa_deptos');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes();
            });
                            
           
        }

         if (!Schema::hasTable('medio_verificacion_planificacion')) {
            Schema::create('medio_verificacion_planificacion', function (Blueprint $table) {
                $table->id();
                $table->string('observacion');

                $table->foreignId('idArchivo')->constrained('archivos');
                $table->foreignId('idActividad')->constrained('actividads');
                $table->foreignId('idPlanificacion')->constrained('planificacions');
                $table->foreignId('idSeguimiento')->constrained('seguimiento_planificacions');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes();
    
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('medio_verificacion_planificacion');
        Schema::dropIfExists('seguimiento_planificacions');
        Schema::dropIfExists('planificacions');
    }
};
