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
        if (!Schema::hasTable('categorias')) {
            Schema::create('categorias', function (Blueprint $table) {
                $table->id();
                $table->string('categoria')->nullable();
                $table->foreignId('created_by')->nullable()->default(null)->constrained('users');
                $table->foreignId('deleted_by')->nullable()->default(null)->constrained('users');
           
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
        if (Schema::hasTable('categorias')) {
            Schema::dropIfExists('categorias');
        }
    }
};