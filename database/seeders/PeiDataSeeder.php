<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Poa\Pei;
use App\Models\Dimension\Dimension;
use App\Models\Objetivos\Objetivo;
use App\Models\Areas\Area;
use App\Models\Resultados\Resultado;
use App\Models\Instituciones\Institucion;

class PeiDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener la primera institución o crearla si no existe
        $institucion = Institucion::first();
        
        if (!$institucion) {
            $institucion = Institucion::create([
                'nombre' => 'Institución Principal',
                'descripcion' => 'Institución principal del sistema',
                'created_by' => 1,
            ]);
        }

        // Crear PEI
        $pei = Pei::create([
            'name' => 'Plan Estratégico Institucional 2024-2028',
            'initialYear' => 2024,
            'finalYear' => 2028,
            'idInstitucion' => $institucion->id,
            'created_by' => 1,
        ]);

        // Crear Dimensión
        $dimension = Dimension::create([
            'nombre' => 'Dimensión Estratégica Principal',
            'descripcion' => 'Dimensión estratégica orientada al desarrollo institucional y mejora continua de los servicios',
            'idPei' => $pei->id,
            'created_by' => 1,
        ]);

        // Crear Objetivo
        $objetivo = Objetivo::create([
            'nombre' => 'Objetivo Estratégico 1',
            'descripcion' => 'Mejorar la calidad y eficiencia de los servicios institucionales mediante la implementación de procesos de mejora continua',
            'idDimension' => $dimension->id,
            'created_by' => 1,
        ]);

        // Crear Área
        $area = Area::create([
            'nombre' => 'Área de Gestión y Calidad',
            'idObjetivo' => $objetivo->id,
            'created_by' => 1,
        ]);

        // Crear Resultado
        $resultado = Resultado::create([
            'nombre' => 'Resultado Esperado 1',
            'descripcion' => 'Incremento del 25% en la satisfacción de los usuarios con los servicios institucionales',
            'idArea' => $area->id,
            'created_by' => 1,
        ]);

        $this->command->info('✅ PEI, Dimensión, Objetivo, Área y Resultado creados exitosamente');
        $this->command->info("   - PEI: {$pei->name}");
        $this->command->info("   - Dimensión: {$dimension->nombre}");
        $this->command->info("   - Objetivo: {$objetivo->nombre}");
        $this->command->info("   - Área: {$area->nombre}");
        $this->command->info("   - Resultado: {$resultado->nombre}");
    }
}
