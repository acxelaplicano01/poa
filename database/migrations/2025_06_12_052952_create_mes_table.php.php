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
       // Tabla mes
        if (!Schema::hasTable('mes')) {
            Schema::create('mes', function (Blueprint $table) {
                $table->id();
                $table->string('mes');
                $table->foreignId('idTrimestre')->constrained('trimestres')->cascadeOnDelete();
                $table->timestamps();
                $table->softDeletes();

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mes');
    }
};
