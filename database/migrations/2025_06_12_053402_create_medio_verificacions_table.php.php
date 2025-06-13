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
       if (!Schema::hasTable('medio_verificacions')) {
            Schema::create('medio_verificacions', function (Blueprint $table) {
                 $table->id();
                    $table->text('nombre');
                    $table->text('descripcion');
                    $table->text('url')->nullable();
                    $table->text('nombre_Archivo')->nullable();

                    $table->foreignId('idSeguimiento')->constrained('seguimiento_tareas');

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
        Schema::dropIfExists('medio_verificacions');
       
    }
};
