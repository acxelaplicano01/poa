<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('poas')) {
            Schema::create('poas', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('anio', 4);

              
                $table->foreignId('idInstitucion')->constrained('institucions');
                $table->foreignId('idUE')->constrained('unidad_ejecutora');

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
        Schema::dropIfExists('poas');
    }
};
