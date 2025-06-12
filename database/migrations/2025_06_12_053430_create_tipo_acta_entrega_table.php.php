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
          if (!Schema::hasTable('tipo_acta_entrega')) {
            Schema::create('tipo_acta_entrega', function (Blueprint $table) {
                $table->id();
                $table->string('tipo')->default('');
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });

            DB::table('tipo_acta_entrega')->insert([
                ['tipo' => 'Final', 'created_at' => now(), 'updated_at' => now()],
                ['tipo' => 'Intermedia', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_acta_entrega');
    }
};
