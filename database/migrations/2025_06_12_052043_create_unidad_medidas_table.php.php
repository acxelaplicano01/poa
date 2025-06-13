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
        // Crear tabla unidadmedidas
         
         if (!Schema::hasTable('unidadmedidas')) {
            Schema::create('unidadmedidas', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();  // created_at, updated_at
                $table->softDeletes(); // deleted_at
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {       
        Schema::dropIfExists('unidadmedidas');       
    }
};
