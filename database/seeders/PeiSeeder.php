<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poa\Pei;
use App\Models\Instituciones\Institucion;

class PeiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunas instituciones
        $instituciones = Institucion::take(3)->get();
        
        if ($instituciones->count() === 0) {
            $this->command->warn('No hay instituciones disponibles. Ejecute el seeder de instituciones primero.');
            return;
        }

        $peis = [
            [
                'name' => 'Plan Estratégico Institucional 2020-2024',
                'initialYear' => 2020,
                'finalYear' => 2024,
                'idInstitucion' => $instituciones->first()->id,
            ],
            [
                'name' => 'PEI Universidad Nacional 2023-2027',
                'initialYear' => 2023,
                'finalYear' => 2027,
                'idInstitucion' => $instituciones->count() > 1 ? $instituciones->get(1)->id : $instituciones->first()->id,
            ],
            [
                'name' => 'Plan Estratégico Ministerial 2025-2030',
                'initialYear' => 2025,
                'finalYear' => 2030,
                'idInstitucion' => $instituciones->count() > 2 ? $instituciones->get(2)->id : $instituciones->first()->id,
            ],
        ];

        foreach ($peis as $peiData) {
            Pei::create($peiData);
        }

        $this->command->info('PEIs de prueba creados exitosamente.');
    }
}
