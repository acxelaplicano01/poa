<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposActividad = [
            ['tipo' => 'TALLERES/SEMINARIOS', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'CONTRATACION DE PERSONAL', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'EQUIPO DE OFICINA', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'EQUIPO TECNOLOGICO', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'ACTIVIDADES ESPECIALES', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'BECAS', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'INFRAESTRUCTURA', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'VENTA DE SERVICIOS', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('tipo_actividads')->insert($tiposActividad);
        
        $this->command->info('Tipos de actividad creados exitosamente.');
    }
}
