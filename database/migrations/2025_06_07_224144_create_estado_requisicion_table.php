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
        if (!Schema::hasTable('estado_requisicion')) {
            Schema::create('estado_requisicion', function (Blueprint $table) {
                $table->id();
                $table->text('estado');
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });

            // Insertar datos iniciales
            $now = Carbon::now();
            DB::table('estado_requisicion')->insert([
                [
                    'estado' => 'Presentado',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'estado' => 'Recibido',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'estado' => 'En Proceso de Compra',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'estado' => 'Aprobado',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'estado' => 'Rechazado',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
       
    }

    public function down(): void
    {
        
        Schema::dropIfExists('estado_requisicion');
    }
};
