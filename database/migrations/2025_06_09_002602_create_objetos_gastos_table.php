<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
         // Tabla objetogastos

        if (!Schema::hasTable('objetogastos')) {
            Schema::create('objetogastos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->longText('descripcion');
                $table->string('identificador');

                $table->foreignId('idgrupo')->constrained('grupogastos');

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
        Schema::dropIfExists('objetogastos');
    }
};

