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
            ['mes' => 1, 'idTrimestre' => 1],  // Enero
            ['mes' => 2, 'idTrimestre' => 1],  // Febrero
            ['mes' => 3, 'idTrimestre' => 1],  // Marzo
            
            // Trimestre 2
            ['mes' => 4, 'idTrimestre' => 2],  // Abril
            ['mes' => 5, 'idTrimestre' => 2],  // Mayo
            ['mes' => 6, 'idTrimestre' => 2],  // Junio
            
            // Trimestre 3
            ['mes' => 7, 'idTrimestre' => 3],  // Julio
            ['mes' => 8, 'idTrimestre' => 3],  // Agosto
            ['mes' => 9, 'idTrimestre' => 3],  // Septiembre
            
            // Trimestre 4
            ['mes' => 10, 'idTrimestre' => 4], // Octubre
            ['mes' => 11, 'idTrimestre' => 4], // Noviembre
            ['mes' => 12, 'idTrimestre' => 4], // Diciembre
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
