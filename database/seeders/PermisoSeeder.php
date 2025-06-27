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
        
        // Estructura jerárquica de permisos de 3 niveles
        // Nivel 1: Módulo padre (acceso-{modulo})
        // Nivel 2: Funcionalidad ({modulo}.{funcionalidad})
        // Nivel 3: Acciones ({modulo}.{funcionalidad}.{accion})
        
        $modulePermissions = [
            'configuracion' => [
                'roles' => ['ver', 'crear', 'editar', 'eliminar'],
                'usuarios' => ['ver', 'crear', 'editar', 'eliminar'],
                'empleados' => ['ver', 'crear', 'editar', 'eliminar'],
                'departamentos' => ['ver', 'crear', 'editar', 'eliminar'],
                'procesoscompras' => ['ver', 'crear', 'editar', 'eliminar'],
                'cubs' => ['ver', 'crear', 'editar', 'eliminar'],
            ],
            'planificacion' => [
                'planificar' => ['ver', 'crear', 'editar'],
                'requerir' => ['ver', 'crear', 'editar'],
                'seguimiento' => ['ver', 'editar'],
                'consolidado' => ['ver', 'generar'],
            ],
            'gestion' => [
                'gestionadministrativa' => ['ver', 'crear', 'editar'],
                'configuracion' => ['ver', 'editar'],
                'plananualcompras' => ['ver', 'crear', 'editar', 'aprobar'],
            ],
            'reportes' => [
                'reportegeneral' => ['ver', 'generar', 'exportar'],
                'resumentrimestral' => ['ver', 'generar', 'exportar'],
                'consolidado' => ['ver', 'generar'],
                'recursosplanificados' => ['ver', 'generar', 'exportar'],
            ],
            'consola' => [
                'planestrategico' => ['ver', 'crear', 'editar', 'aprobar'],
                'asignacionpresupuestaria' => ['ver', 'crear', 'editar', 'aprobar'],
            ],
            'logs' => [
                'visor' => ['ver', 'filtrar', 'exportar'],
                'dashboard' => ['ver', 'analizar'],
                'mantenimiento' => ['limpiar'],
                'sessions' => ['ver', 'gestionar'],
            ]
        ];
        
        // 1. Crear permisos de acceso a módulos (Nivel 1)
        $modules = array_keys($modulePermissions);
        foreach ($modules as $module) {
            Permission::firstOrCreate([
                'name' => "acceso-{$module}", 
                'guard_name' => 'web'
            ]);
        }
        
        // 2. Crear permisos específicos por funcionalidad y acción (Niveles 2 y 3)
        foreach ($modulePermissions as $module => $functionalities) {
            foreach ($functionalities as $functionality => $actions) {
                // Crear permiso de funcionalidad (Nivel 2) - opcional
                // Permission::firstOrCreate([
                //     'name' => "{$module}.{$functionality}", 
                //     'guard_name' => 'web'
                // ]);
                
                // Crear permisos de acciones específicas (Nivel 3)
                foreach ($actions as $action) {
                    Permission::firstOrCreate([
                        'name' => "{$module}.{$functionality}.{$action}", 
                        'guard_name' => 'web'
                    ]);
                }
            }
        }
        
        // 3. Otros permisos independientes que no siguen la estructura jerárquica
        $otherPermissions = [
            'dashboard.ver', // Acceso al dashboard principal
            // Agregar aquí otros permisos que no sigan la estructura modular
        ];
        
        foreach ($otherPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission, 
                'guard_name' => 'web'
            ]);
        }
        
        $this->command->info('Permisos creados exitosamente con estructura jerárquica de 3 niveles.');
    }
}