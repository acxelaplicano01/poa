<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidadesMedida = [
            ['nombre' => 'Litros', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Kilogramos', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Gramos', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Unidad', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Mililitros', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Consultoría', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Día', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Docena', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Galón', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Lance', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Libras', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Metro Lineal', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Metro Cuadrado', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Metro Cubico', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Pies', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Quintal', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Quinto', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Resma', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Barril', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Boleto', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Bolsa', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Bomba', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Bote', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Caja', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cuarto', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cubeta', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Fardo', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Frasco', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Hora', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Litro', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Paquete', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Par', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Pieza', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Plato', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Pliego', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Publicación', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Rollo', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'SET', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Tarifa', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Mes', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Yarda', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('unidadmedidas')->insert($unidadesMedida);
        
        $this->command->info('Unidades de medida creadas exitosamente.');
    }
}
