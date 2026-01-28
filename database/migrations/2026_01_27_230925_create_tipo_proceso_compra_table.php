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
        if (!Schema::hasTable('tipo_proceso_compra')) {
            Schema::create('tipo_proceso_compra', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 100);
                $table->text('descripcion')->nullable();
                $table->decimal('monto_minimo', 15, 2);
                $table->decimal('monto_maximo', 15, 2)->nullable();
                $table->boolean('activo')->default(true);
                $table->unsignedBigInteger('idPoa')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_proceso_compra');
    }
};
