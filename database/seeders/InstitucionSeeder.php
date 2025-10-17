<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Instituciones\Institucions;

class InstitucionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('institucions')->insert([
            'nombre' => 'UNAH',
            'descripcion' => 'Universidad Nacional Autónoma de Honduras',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}