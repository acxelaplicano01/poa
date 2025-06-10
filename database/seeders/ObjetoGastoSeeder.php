<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GrupoGastos\ObjetoGastos;
use App\Models\GrupoGastos\GrupoGastos;

class ObjetoGastoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('objetogastos')->insert([
            [
                'nombre' => "Sueldos Básicos",
                'identificador' => 11100,
                'idgrupo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Sueldos Básicos Educación",
                'identificador' => 11200,
                'idgrupo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => "Sueldos Básicos Docentes",
                'identificador' => 11210,
                'idgrupo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}