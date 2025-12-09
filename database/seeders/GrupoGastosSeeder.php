<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupoGastosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gruposGastos = [
            ['nombre' => 'Servicios Personales', 'identificador' => 1],
            ['nombre' => 'Servicios no Personales', 'identificador' => 2],
            ['nombre' => 'Materiales y Suministros', 'identificador' => 3],
            ['nombre' => 'Bienes Capitalizables', 'identificador' => 4],
            ['nombre' => 'Transferencias y Donaciones', 'identificador' => 5],
            ['nombre' => 'Activos Financieros', 'identificador' => 6],
            ['nombre' => 'Servicio de la Deuda Pública', 'identificador' => 7],
            ['nombre' => 'Otros Gastos', 'identificador' => 8],
        ];

        foreach ($gruposGastos as $grupo) {
            DB::table('grupogastos')->insert([
                'nombre' => $grupo['nombre'],
                'identificador' => $grupo['identificador'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Grupos de gastos creados exitosamente.');
    }
}
