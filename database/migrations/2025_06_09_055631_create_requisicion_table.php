<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
    }

    public function down(): void
    {
        Scheme::dropIfExists('requisicion');
    }
};
