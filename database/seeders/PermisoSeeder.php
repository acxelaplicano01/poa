<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear roles y permisos en caché
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $permissions = [
            'acceso-configuracion',
            'acceso-planificacion',
            'configuracion.roles',
            'admin-admin-permiso',
            'configuracion.usuarios',
            'planificacion.planificar',
            'admin-admin-configuracion',
            'admin-admin-dashboard',
            'Gestion_Reportes',
            'Gestion_PEI',
            'Crear_PEI',
            'Gestion_Revisiones',
            'Gestion_POA',
            'Crear_POA',
            'Deshabilitar_POA',
            'Gestion_RRHH',
            'Gestion_Seguimientos',
            'Gestion_MIS_POAS',
         ];
         
         foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
         }
    }
}