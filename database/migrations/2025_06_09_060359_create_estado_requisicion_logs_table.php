<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
         // Crear tabla estado_requisicion_logs

        if (!Schema::hasTable('estado_requisicion_logs')) {
            Schema::create('estado_requisicion_logs', function (Blueprint $table) {
                $table->id();
                $table->string('observacion');
                $table->string('log');
                $table->foreignId('idRequisicion')->constrained('requisicion');
                $table->foreignId('created_by')->constrained('users');
               
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });
        }

    }

    public function down(): void
    {
        Schema::dropIfexists('estado_requisicion_logs');
    }
};
