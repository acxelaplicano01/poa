<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       
        //Tabla cubs
        
        if (!Schema::hasTable('cubs')) {
            Schema::create('cubs', function (Blueprint $table) {
                $table->id();

                $table->string('IDUNSPSC');
                $table->string('descripcion_esp');
                $table->string('descripcion_regional');

                $table->foreignId('idUE')
                      ->constrained('unidad_ejecutora')
                      ->onDelete('cascade');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
         
        }       
    }

    public function down(): void
    { 
        Schema::dropIfExists('cubs');
    }
};
