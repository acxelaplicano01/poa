<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
         if (!Schema::hasTable('archivos')) {
            Schema::create('archivos', function (Blueprint $table) {
                $table->id();

                $table->string('nombre_archivo');
                $table->string('url');
                $table->string('url_alternativa')->nullable();

                $table->foreignId('subido_por')->nullable()->constrained('users');
                $table->foreignId('idUnidadEjecutora')->nullable()->constrained('unidad_ejecutora');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes(); // deleted_at
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('archivos');
    }
};
