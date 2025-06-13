<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Instituciones\Institucions;

class UnidadEjecutoraSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('unidad_ejecutora')->insert([
            'name' => 'CURLP',
            'descripcion' => 'Centro Universitario Regional del Litoral Pacífico - UNAH',
            'estructura' => '0-00-00-00',
            'idInstitucion' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}