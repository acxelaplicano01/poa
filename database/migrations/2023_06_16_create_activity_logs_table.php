<?php
// filepath: c:\Users\acxel\Desktop\Desarrollo\Git Repos\POA\database\migrations\2023_06_16_create_activity_logs_table.php

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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name');
            $table->string('ip_address', 45)->nullable();
            $table->string('module');
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('data')->nullable();
            $table->string('level')->default('info');
            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->index('module');
            $table->index('action');
            $table->index('level');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};