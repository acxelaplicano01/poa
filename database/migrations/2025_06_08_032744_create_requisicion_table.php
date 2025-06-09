<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('requisicion')) {
            Schema::create('requisicion', function (Blueprint $table) {
                $table->id();
                $table->text('correlativo');
                $table->text('descripcion');
                $table->text('observacion');

                $table->foreignId('createdBy')->constrained('users');
                $table->foreignId('approvedBy')->nullable()->constrained('users');

                $table->foreignId('idPoa')->constrained('poas');
                $table->foreignId('idDepartamento')->constrained('departamentos');
                $table->foreignId('idEstado')->constrained('estado_requisicion');

                $table->dateTime('fechaSolicitud')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->date('fechaRequerido');

                
                $table->softDeletes(); 
                $table->timestamps();
            });
        }

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

    public function down(): void
    {
        Schema::dropIfExists('unidadmedidas');
        Schema::dropIfExists('estado_requisicion_logs');
        Schema::dropIfExists('requisicion');
    }
};

