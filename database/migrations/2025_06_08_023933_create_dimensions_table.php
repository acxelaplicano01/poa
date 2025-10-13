<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla dimensions 
        if (!Schema::hasTable('dimensions')) {
            Schema::create('dimensions', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('descripcion');
                //$table->foreignId('idPei')->constrained('peis');

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
        ;
        Schema::dropIfExists('dimensions');
    }
};
