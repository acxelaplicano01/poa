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

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimiento_planificacions');
    }
};
