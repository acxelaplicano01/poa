<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MesesSeeder extends Seeder
{
    public function run()
    {
        $meses = [
            // Trimestre 1
            ['mes' => 'Enero', 'idTrimestre' => 1],  // Enero
            ['mes' => 'Febrero', 'idTrimestre' => 1],  // Febrero
            ['mes' => 'Marzo', 'idTrimestre' => 1],  // Marzo
            
            // Trimestre 2
            ['mes' => 'Abril', 'idTrimestre' => 2],  // Abril
            ['mes' => 'Mayo', 'idTrimestre' => 2],  // Mayo
            ['mes' => 'Junio', 'idTrimestre' => 2],  // Junio
            
            // Trimestre 3
            ['mes' => 'Julio', 'idTrimestre' => 3],  // Julio
            ['mes' => 'Agosto', 'idTrimestre' => 3],  // Agosto
            ['mes' => 'Septiembre', 'idTrimestre' => 3],  // Septiembre
            
            // Trimestre 4
            ['mes' => 'Octubre', 'idTrimestre' => 4], // Octubre
            ['mes' => 'Noviembre', 'idTrimestre' => 4], // Noviembre
            ['mes' => 'Diciembre', 'idTrimestre' => 4], // Diciembre
        ];

        foreach ($meses as $mes) {
            DB::table('mes')->insert([
                'mes' => $mes['mes'],
                'idTrimestre' => $mes['idTrimestre'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('12 meses creados exitosamente.');
    }
}
