<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GrupoGastos\GrupoGastos;
use App\Models\UnidadEjecutora\UnidadEjecutora;

class GrupoGastoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('grupogastos')->insert([
            [
                'nombre' => "Servicios Personales",
                'identificador' => 1000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Servicios no Personales",
                'identificador' => 2000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Materiales y Suministros",
                'identificador' => 3000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Bienes Capitalizables",
                'identificador' => 4000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Transferencias y Donaciones",
                'identificador' => 5000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Activos Financieros",
                'identificador' => 6000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Servicio de la Deuda Pública",
                'identificador' => 7000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Otros Gastos",
                'identificador' => 9000,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}