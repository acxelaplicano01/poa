<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlazosPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener o crear permiso para gestión de plazos
        $permission = DB::table('permissions')
            ->where('name', 'consola.plazos.gestionar')
            ->first();

        if (!$permission) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name' => 'consola.plazos.gestionar',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('Permiso "consola.plazos.gestionar" creado');
        } else {
            $permissionId = $permission->id;
            $this->command->info('Permiso "consola.plazos.gestionar" ya existe');
        }

        // Obtener el rol admin_general
        $adminRole = DB::table('roles')->where('name', 'admin_general')->first();

        if ($adminRole) {
            // Verificar si ya está asignado
            $exists = DB::table('role_has_permissions')
                ->where('permission_id', $permissionId)
                ->where('role_id', $adminRole->id)
                ->exists();

            if (!$exists) {
                // Asignar permiso al rol
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $adminRole->id,
                ]);
                $this->command->info('Permiso asignado al rol admin_general');
            } else {
                $this->command->info('El permiso ya está asignado al rol admin_general');
            }
        } else {
            $this->command->error('No se encontró el rol admin_general');
        }
    }
}

