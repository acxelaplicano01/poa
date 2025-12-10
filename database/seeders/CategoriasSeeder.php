<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['categoria' => 'PROGRAMAS/PROYECTOS', 'created_at' => now(), 'updated_at' => now()],
            ['categoria' => 'OPERACIONES', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('categorias')->insert($categorias);

        $this->command->info('Categorías creadas exitosamente.');
    }
}
