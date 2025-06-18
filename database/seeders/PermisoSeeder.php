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
        
        // Permisos organizados por módulo
        $modulePermissions = [
            'configuracion' => [
                'roles',
                'usuarios',
                'empleados',
                'departamentos',
                'procesoscompras',
                'cubs',
                // Agregar más subpermisos aquí
            ],
            'planificacion' => [
                'planificar',
                'requerir',
                'seguimiento',
                'consolidado',
                // Agregar más subpermisos aquí
            ],
            'gestion' => [
                'gestionadministrativa',
                'configuracion',
                'plananualcompras',
                // Agregar más subpermisos aquí
            ],
            'reportes' => [
                'reportegeneral',
                'resumentrimestral',
                'consolidado',
                'recursosplanificados',
                // Subpermisos de reportes
            ],
            'consolas' => [
                'planestrategico',
                'asignacionpresupuestaria',
                // Subpermisos de consolas
            ],
            'logs' => [
                'ver-logs',
                'ver-logs-dashboard',
                'ver-logs-show',
                'ver-logs-cleanup',
            ]
        ];
        
        // Permisos de acceso a módulos (estos son los permisos principales)
        $modules = array_keys($modulePermissions);
        foreach ($modules as $module) {
            Permission::firstOrCreate(['name' => "acceso-{$module}", 'guard_name' => 'web']);
        }
        
        // Permisos específicos por módulo (estos son los subpermisos)
        foreach ($modulePermissions as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => "{$module}.{$permission}", 'guard_name' => 'web']);
            }
        }
        
        // Otros permisos que no siguen la estructura jerárquica
        $otherPermissions = [
            'admin-admin-permiso',
            'admin-admin-configuracion',
            'admin-admin-dashboard',
            // Cualquier otro permiso que no siga el formato de módulo
        ];
        
        foreach ($otherPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}