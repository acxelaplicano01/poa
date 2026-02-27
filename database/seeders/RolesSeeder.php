<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {

        // Resetear roles y permisos en caché
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('roles')->truncate();

        DB::table('roles')->insert([
            [
                'id'          => 1,
                'name'        => 'super_admin',
                'description' => 'super usuario',
                'guard_name'  => 'web',
                'created_at'  => '2023-07-03 19:10:47',
                'updated_at'  => '2023-07-03 19:10:47',
            ],
            [
                'id'          => 2,
                'name'        => 'admin',
                'description' => 'administrador',
                'guard_name'  => 'web',
                'created_at'  => '2023-07-03 19:10:47',
                'updated_at'  => '2023-07-03 19:10:47',
            ],
            [
                'id'          => 3,
                'name'        => 'planificador',
                'description' => 'Jefe de Departamento o coordinador asignado que llevará la planificación',
                'guard_name'  => 'web',
                'created_at'  => '2023-07-03 19:10:47',
                'updated_at'  => '2023-07-03 19:10:47',
            ],
            [
                'id'          => 4,
                'name'        => 'Asistente de recursos',
                'description' => '-----',
                'guard_name'  => 'web',
                'created_at'  => '2023-07-19 16:57:17',
                'updated_at'  => '2023-07-19 16:57:17',
            ],
            [
                'id'          => 5,
                'name'        => 'Dirección',
                'description' => 'Perfil para director de la unidad ejecutora',
                'guard_name'  => 'web',
                'created_at'  => '2023-07-25 19:27:45',
                'updated_at'  => '2023-07-25 19:27:45',
            ],
            [
                'id'          => 6,
                'name'        => 'Consolidador',
                'description' => 'asdas',
                'guard_name'  => 'web',
                'created_at'  => '2023-08-03 20:00:37',
                'updated_at'  => '2023-08-03 20:00:37',
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
