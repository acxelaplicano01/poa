<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrimestresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trimestres = [
            ['trimestre' => 'Primer Trimestre', 'created_at' => now(), 'updated_at' => now()],
            ['trimestre' => 'Segundo Trimestre', 'created_at' => now(), 'updated_at' => now()],
            ['trimestre' => 'Tercer Trimestre', 'created_at' => now(), 'updated_at' => now()],
            ['trimestre' => 'Cuarto Trimestre', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('trimestres')->insert($trimestres);
    }
}