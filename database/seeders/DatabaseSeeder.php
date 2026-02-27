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
use Database\Seeders\ObjetoGastosSeeder;
use Database\Seeders\CubSeeder;
use App\Models\Area;
use Database\Seeders\TechoDeptoSeeder;
use Database\Seeders\TechoUesSeeder;
use Database\Seeders\DepartamentoSeeder;
use Database\Seeders\PoasSeeder;
use Database\Seeders\PoaDeptoSeeder;
use Database\Seeders\EmpleadosSeeder;
use Database\Seeders\EmpleadoDeptoSeeder;
use Database\Seeders\ActividadesSeeder;
use Database\Seeders\IndicadoresSeeder;
use Database\Seeders\PlanificacionsSeeder;
use Database\Seeders\EmpleadoActividadsSeeder;
use Database\Seeders\TareasSeeder;
use Database\Seeders\PresupuestosSeeder;
use Database\Seeders\RevisionesSeeder;
use Database\Seeders\EstadoRequisicionLogsSeeder;
use Database\Seeders\RequisicionSeeder;
use Database\Seeders\DetalleRequisicionSeeder;
use Database\Seeders\EjecucionPresupuestariaSeeder;
use Database\Seeders\EjecucionPresupuestariaLogsSeeder;
use Database\Seeders\DetalleEjecucionPresupuestariaSeeder;
use Database\Seeders\OrdenCombustibleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RolesSeeder::class);
        $this->call(PermisoSeeder::class);
        $this->call(UsuarioTablaSeeder::class);
        $this->call(UsuariosSeeder::class);
        $this->call(InstitucionSeeder::class);
        $this->call(UnidadEjecutoraSeeder::class);
        $this->call(FuenteSeeder::class);
        $this->call(DepartamentoSeeder::class);
        $this->call(TrimestresSeeder::class);
        $this->call(MesesSeeder::class);
        $this->call(TipoActividadSeeder::class);
        $this->call(UnidadMedidaSeeder::class);
        $this->call(CategoriasSeeder::class);
        $this->call(CubSeeder::class);
        $this->call(GrupoGastoSeeder::class);
        $this->call(ObjetoGastosSeeder::class);
        $this->call(PeiDataSeeder::class);
        $this->call(PeiSeeder::class);
        $this->call(ProcesoCompraSeeder::class);
        $this->call(RecursoSeeder::class);

        
        $this->call(TechoDeptoSeeder::class);
        $this->call(TechoUesSeeder::class);
        $this->call(PoasSeeder::class);
        $this->call(PoaDeptoSeeder::class);
        $this->call(EmpleadosSeeder::class);
        $this->call(EmpleadoDeptoSeeder::class);
        $this->call(ActividadesSeeder::class);
        $this->call(IndicadoresSeeder::class);
        $this->call(PlanificacionsSeeder::class);
        $this->call(EmpleadoActividadsSeeder::class);
        $this->call(TareasSeeder::class);
        $this->call(PresupuestosSeeder::class);
        $this->call(RevisionesSeeder::class);
        $this->call(EstadoRequisicionLogsSeeder::class);
        $this->call(RequisicionSeeder::class);
        $this->call(DetalleRequisicionSeeder::class);
        $this->call(EjecucionPresupuestariaSeeder::class);
        $this->call(EjecucionPresupuestariaLogsSeeder::class);
        $this->call(DetalleEjecucionPresupuestariaSeeder::class);
        $this->call(OrdenCombustibleSeeder::class);
        




        
    }
}
