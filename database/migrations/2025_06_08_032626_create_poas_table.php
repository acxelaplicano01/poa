<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Crear tabla poas
        
        if (!Schema::hasTable('poas')) {
            Schema::create('poas', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('anio', 4);

              
                $table->foreignId('idInstitucion')->constrained('institucions');
                $table->foreignId('idUE')->constrained('unidad_ejecutoras');

                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');

                $table->timestamps();
                $table->softDeletes(); 
            });
        }

        // Crear tabla poa_deptos
        if (!Schema::hasTable('poa_deptos')) {
            Schema::create('poa_deptos', function (Blueprint $table) {
                $table->id();
                $table->boolean('isActive')->default(true);

                $table->foreignId('idPoaUE')->constrained('poas');
                $table->foreignId('idDepto')->constrained('deptos');
                
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
        Schema::dropIfExists('poa_deptos');
        Schema::dropIfExists('poas');
    }
};
