<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('estado_ejecucion_presupuestaria')) {
            Schema::create('estado_ejecucion_presupuestaria', function (Blueprint $table) {
                $table->id();
                $table->text('estado');
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });

            // Insertar registros sin claves foráneas
            $now = Carbon::now();
            DB::table('estado_ejecucion_presupuestaria')->insert([
                [
                    'estado' => 'Parcialmente ejecutado',
                    'created_by' => null,
                    'updated_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'estado' => 'Ejecutado',
                    'created_by' => null,
                    'updated_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'estado' => 'Ejecucion cancelada',
                    'created_by' => null,
                    'updated_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'estado' => 'Ejecucion finalizada',
                    'created_by' => null,
                    'updated_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('estado_ejecucion_presupuestaria');
    }
};
