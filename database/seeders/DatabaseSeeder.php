<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PermisoSeeder;
use Database\Seeders\UsuarioTablaSeeder;
use Database\Seeders\InstitucionSeeder;
use Database\Seeders\UnidadEjecutoraSeeder;
use Database\Seeders\FuenteSeeder;
use Database\Seeders\GrupoGastoSeeder;
use Database\Seeders\ObjetoGastoSeeder;
use Database\Seeders\CubSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RoleSeeder::class);
        $this->call(PermisoSeeder::class);
        $this->call(UsuarioTablaSeeder::class);
        $this->call(InstitucionSeeder::class);
        $this->call(UnidadEjecutoraSeeder::class);
        $this->call(FuenteSeeder::class);
        $this->call(DepartamentoSeeder::class);
        $this->call(MesesSeeder::class);
        $this->call(TrimestresSeeder::class);
        //$this->call(GrupoGastoSeeder::class);
        //$this->call(ObjetoGastoSeeder::class);
        //$this->call(CubSeeder::class);
    }
}
