<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GrupoGastos\Fuente;

class FuenteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fuente')->insert([
            [
                'nombre' => "Ingresos del estado",
                'identificador' => "11",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Ahorros",
                'identificador' => "12",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Ingresos propios",
                'identificador' => "12B",
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}