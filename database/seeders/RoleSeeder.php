<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Resetear roles y permisos en caché
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Primero, crear todos los permisos necesarios si no existen
     /*   $allPermissions = [
            'acceso-configuracion',
            'acceso-consola',
            'acceso-planificacion',
            'acceso-revision',
            'configuracion.categorias.crear',
            'configuracion.categorias.editar',
            'configuracion.categorias.eliminar',
            'configuracion.categorias.ver',
            'configuracion.cubs.crear',
            'configuracion.cubs.editar',
            'configuracion.cubs.eliminar',
            'configuracion.cubs.ver',
            'configuracion.departamentos.crear',
            'configuracion.departamentos.editar',
            'configuracion.departamentos.eliminar',
            'configuracion.departamentos.ver',
            'configuracion.empleados.crear',
            'configuracion.empleados.editar',
            'configuracion.empleados.eliminar',
            'configuracion.empleados.ver',
            'configuracion.estadosejecucion.crear',
            'configuracion.estadosejecucion.editar',
            'configuracion.estadosejecucion.eliminar',
            'configuracion.estadosejecucion.ver',
            'configuracion.estadosrequisicion.crear',
            'configuracion.estadosrequisicion.editar',
            'configuracion.estadosrequisicion.eliminar',
            'configuracion.estadosrequisicion.ver',
            'configuracion.fuentes.crear',
            'configuracion.fuentes.editar',
            'configuracion.fuentes.eliminar',
            'configuracion.fuentes.ver',
            'configuracion.grupogastos.crear',
            'configuracion.grupogastos.editar',
            'configuracion.grupogastos.eliminar',
            'configuracion.grupogastos.ver',
            'configuracion.instituciones.crear',
            'configuracion.instituciones.editar',
            'configuracion.instituciones.eliminar',
            'configuracion.instituciones.ver',
            'configuracion.procesoscompras.crear',
            'configuracion.procesoscompras.editar',
            'configuracion.procesoscompras.eliminar',
            'configuracion.procesoscompras.ver',
            'configuracion.recursos.crear',
            'configuracion.recursos.editar',
            'configuracion.recursos.eliminar',
            'configuracion.recursos.ver',
            'configuracion.roles.crear',
            'configuracion.roles.editar',
            'configuracion.roles.eliminar',
            'configuracion.roles.ver',
            'configuracion.tipoactaentregas.crear',
            'configuracion.tipoactaentregas.editar',
            'configuracion.tipoactaentregas.eliminar',
            'configuracion.tipoactaentregas.ver',
            'configuracion.tipoactividades.crear',
            'configuracion.tipoactividades.editar',
            'configuracion.tipoactividades.eliminar',
            'configuracion.tipoactividades.ver',
            'configuracion.trimestres.crear',
            'configuracion.trimestres.editar',
            'configuracion.trimestres.eliminar',
            'configuracion.trimestres.ver',
            'configuracion.unidades-ejecutoras.crear',
            'configuracion.unidades-ejecutoras.editar',
            'configuracion.unidades-ejecutoras.eliminar',
            'configuracion.unidades-ejecutoras.ver',
            'configuracion.unidadmedidas.crear',
            'configuracion.unidadmedidas.editar',
            'configuracion.unidadmedidas.eliminar',
            'configuracion.unidadmedidas.ver',
            'configuracion.usuarios.crear',
            'configuracion.usuarios.editar',
            'configuracion.usuarios.eliminar',
            'configuracion.usuarios.ver',
            'consola.areas.crear',
            'consola.areas.editar',
            'consola.areas.eliminar',
            'consola.areas.ver',
            'consola.asignacionnacionalpresupuestaria.crear',
            'consola.asignacionnacionalpresupuestaria.editar',
            'consola.asignacionnacionalpresupuestaria.eliminar',
            'consola.asignacionnacionalpresupuestaria.asignar',
            'consola.asignacionnacionalpresupuestaria.ver',
            'consola.asignacionpresupuestaria.crear',
            'consola.asignacionpresupuestaria.editar',
            'consola.asignacionpresupuestaria.eliminar',
            'consola.asignacionpresupuestaria.ver',
            'consola.asignacionpresupuestaria.asignar',
            'consola.dimensiones.crear',
            'consola.dimensiones.editar',
            'consola.dimensiones.eliminar',
            'consola.dimensiones.ver',
            'consola.objetivos.crear',
            'consola.objetivos.editar',
            'consola.objetivos.eliminar',
            'consola.objetivos.ver',
            'consola.planestrategicoinstitucional.crear',
            'consola.planestrategicoinstitucional.editar',
            'consola.planestrategicoinstitucional.eliminar',
            'consola.planestrategicoinstitucional.ver',
            'consola.plazos.crear',
            'consola.plazos.editar',
            'consola.plazos.eliminar',
            'consola.plazos.gestionar',
            'consola.techodeptos.crear',
            'consola.techodeptos.editar',
            'consola.techodeptos.eliminar',
            'consola.techodeptos.ver',
            'consola.techonacional.ver',
            'logs.dashboard.ver',
            'logs.mantenimiento.limpiar',
            'logs.sessions.ver',
            'logs.visor.ver',
            'planificacion.actividades.crear',
            'planificacion.actividades.editar',
            'planificacion.actividades.eliminar',
            'planificacion.actividades.ver',
            'planificacion.actividades.gestionar',
            'planificacion.consolidado.crear',
            'planificacion.consolidado.editar',
            'planificacion.consolidado.eliminar',
            'planificacion.consolidado.ver',
            'planificacion.planificar.crear',
            'planificacion.planificar.editar',
            'planificacion.planificar.eliminar',
            'planificacion.planificar.ver',
            'planificacion.requisicion.crear',
            'planificacion.requisicion.editar',
            'planificacion.requisicion.eliminar',
            'planificacion.requisicion.ver',
            'planificacion.seguimiento.crear',
            'planificacion.seguimiento.editar',
            'planificacion.seguimiento.eliminar',
            'planificacion.seguimiento.ver',
            'revision.gestionar',
            'revision.ver',
        ];

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        
        $this->command->info('✓ Permisos creados: ' . count($allPermissions));
        
        // ==================== ROL: SUPER_ADMIN ====================
        // Acceso completo a todo el sistema
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());
        
        // ==================== ROL: ADMIN ====================
        // Gestion_Reportes, Gestion_Revisiones, Gestion_POA, Gestion_Usuarios, 
        // Gestion_RRHH, Gestion_Seguimientos, Gestion_MIS_POAS
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $adminPermissions = [
            // Accesos principales
            'acceso-configuracion',
            'acceso-planificacion',
            
            // Gestion_Reportes (Logs)
            'logs.visor.ver',
            'logs.dashboard.ver',
            'logs.sessions.ver',
            
            // Gestion_Revisiones
            'revision.ver',
            'revision.gestionar',
            
            // Gestion_POA (Planificación completa)
            'planificacion.planificar.ver',
            'planificacion.planificar.crear',
            'planificacion.planificar.editar',
            'planificacion.planificar.eliminar',
            'planificacion.actividades.ver',
            'planificacion.actividades.crear',
            'planificacion.actividades.editar',
            'planificacion.actividades.eliminar',
            'planificacion.actividades.gestionar',
            'planificacion.requisicion.ver',
            'planificacion.requisicion.crear',
            'planificacion.requisicion.editar',
            'planificacion.requisicion.eliminar',
            
            // Gestion_Seguimientos
            'planificacion.seguimiento.ver',
            'planificacion.seguimiento.crear',
            'planificacion.seguimiento.editar',
            'planificacion.seguimiento.eliminar',
            
            // Gestion_MIS_POAS
            'planificacion.consolidado.ver',
            'planificacion.consolidado.crear',
            'planificacion.consolidado.editar',
            'planificacion.consolidado.eliminar',
            
            // Gestion_Usuarios
            'configuracion.usuarios.ver',
            'configuracion.usuarios.crear',
            'configuracion.usuarios.editar',
            'configuracion.usuarios.eliminar',
            'configuracion.roles.ver',
            'configuracion.roles.crear',
            'configuracion.roles.editar',
            'configuracion.roles.eliminar',
            
            // Gestion_RRHH
            'configuracion.empleados.ver',
            'configuracion.empleados.crear',
            'configuracion.empleados.editar',
            'configuracion.empleados.eliminar',
            'configuracion.departamentos.ver',
            'configuracion.departamentos.crear',
            'configuracion.departamentos.editar',
            'configuracion.departamentos.eliminar',
            'configuracion.unidades-ejecutoras.ver',
            'configuracion.unidades-ejecutoras.crear',
            'configuracion.unidades-ejecutoras.editar',
            'configuracion.unidades-ejecutoras.eliminar',
            
            // Configuración general (catálogos base)
            'configuracion.categorias.ver',
            'configuracion.categorias.crear',
            'configuracion.categorias.editar',
            'configuracion.categorias.eliminar',
            'configuracion.trimestres.ver',
            'configuracion.trimestres.crear',
            'configuracion.trimestres.editar',
            'configuracion.trimestres.eliminar',
            'configuracion.tipoactividades.ver',
            'configuracion.tipoactividades.crear',
            'configuracion.tipoactividades.editar',
            'configuracion.tipoactividades.eliminar',
            'configuracion.tipoactaentregas.ver',
            'configuracion.tipoactaentregas.crear',
            'configuracion.tipoactaentregas.editar',
            'configuracion.tipoactaentregas.eliminar',
            'configuracion.unidadmedidas.ver',
            'configuracion.unidadmedidas.crear',
            'configuracion.unidadmedidas.editar',
            'configuracion.unidadmedidas.eliminar',
            'configuracion.estadosejecucion.ver',
            'configuracion.estadosejecucion.crear',
            'configuracion.estadosejecucion.editar',
            'configuracion.estadosejecucion.eliminar',
            'configuracion.estadosrequisicion.ver',
            'configuracion.estadosrequisicion.crear',
            'configuracion.estadosrequisicion.editar',
            'configuracion.estadosrequisicion.eliminar',
            'configuracion.fuentes.ver',
            'configuracion.fuentes.crear',
            'configuracion.fuentes.editar',
            'configuracion.fuentes.eliminar',
            'configuracion.grupogastos.ver',
            'configuracion.grupogastos.crear',
            'configuracion.grupogastos.editar',
            'configuracion.grupogastos.eliminar',
            'configuracion.instituciones.ver',
            'configuracion.instituciones.crear',
            'configuracion.instituciones.editar',
            'configuracion.instituciones.eliminar',
            'configuracion.procesoscompras.ver',
            'configuracion.procesoscompras.crear',
            'configuracion.procesoscompras.editar',
            'configuracion.procesoscompras.eliminar',
            'configuracion.recursos.ver',
            'configuracion.recursos.crear',
            'configuracion.recursos.editar',
            'configuracion.recursos.eliminar',
            'configuracion.cubs.ver',
            'configuracion.cubs.crear',
            'configuracion.cubs.editar',
            'configuracion.cubs.eliminar',
        ];
        $admin->syncPermissions($adminPermissions);
        
        // ==================== ROL: PLANIFICADOR ====================
        // Gestion_Reportes, Gestion_Seguimientos, Gestion_MIS_POAS
        $planificador = Role::firstOrCreate(['name' => 'planificador']);
        $planificadorPermissions = [
            // Acceso a planificación
            'acceso-planificacion',
            
            // Gestion_Reportes
            'logs.visor.ver',
            'logs.dashboard.ver',
            
            // Gestion_POA (solo lectura y operación básica)
            'planificacion.planificar.ver',
            'planificacion.planificar.crear',
            'planificacion.planificar.editar',
            'planificacion.planificar.eliminar',
            'planificacion.actividades.ver',
            'planificacion.actividades.crear',
            'planificacion.actividades.editar',
            'planificacion.actividades.eliminar',
            'planificacion.actividades.gestionar',
            'planificacion.requisicion.ver',
            'planificacion.requisicion.crear',
            'planificacion.requisicion.editar',
            'planificacion.requisicion.eliminar',
            
            // Gestion_Seguimientos
            'planificacion.seguimiento.ver',
            'planificacion.seguimiento.crear',
            'planificacion.seguimiento.editar',
            'planificacion.seguimiento.eliminar',
            
            // Gestion_MIS_POAS
            'planificacion.consolidado.ver',
            'planificacion.consolidado.crear',
            'planificacion.consolidado.editar',
            'planificacion.consolidado.eliminar',
        ];
        $planificador->syncPermissions($planificadorPermissions);
        
        // ==================== ROL: DIRECCION ====================
        // Gestion_PEI, Crear_PEI, Gestion_Reportes, Gestion_Revisiones, Gestion_POA,
        // Crear_POA, Deshabilitar_POA, Gestion_Usuarios, Gestion_RRHH, 
        // Gestion_Seguimientos, Gestion_MIS_POAS
        $direccion = Role::firstOrCreate(['name' => 'direccion']);
        $direccionPermissions = [
            // Accesos principales
            'acceso-configuracion',
            'acceso-consola',
            'acceso-planificacion',
            
            // Gestion_PEI y Crear_PEI
            'consola.planestrategicoinstitucional.ver',
            'consola.planestrategicoinstitucional.crear',
            'consola.planestrategicoinstitucional.editar',
            'consola.planestrategicoinstitucional.eliminar',
            'consola.dimensiones.ver',
            'consola.dimensiones.crear',
            'consola.dimensiones.editar',
            'consola.dimensiones.eliminar',
            'consola.objetivos.ver',
            'consola.objetivos.crear',
            'consola.objetivos.editar',
            'consola.objetivos.eliminar',
            'consola.areas.ver',
            'consola.areas.crear',
            'consola.areas.editar',
            'consola.areas.eliminar',
            
            // Gestión Presupuestaria
            'consola.asignacionnacionalpresupuestaria.ver',
            'consola.asignacionnacionalpresupuestaria.crear',
            'consola.asignacionnacionalpresupuestaria.editar',
            'consola.asignacionnacionalpresupuestaria.eliminar',
            'consola.asignacionnacionalpresupuestaria.asignar',
            'consola.asignacionpresupuestaria.ver',
            'consola.asignacionpresupuestaria.crear',
            'consola.asignacionpresupuestaria.editar',
            'consola.asignacionpresupuestaria.eliminar',
            'consola.asignacionpresupuestaria.asignar',
            'consola.techodeptos.ver',
            'consola.techodeptos.crear',
            'consola.techodeptos.editar',
            'consola.techodeptos.eliminar',
            'consola.techonacional.ver',
            
            // Deshabilitar_POA (Plazos)
            'consola.plazos.gestionar',
            'consola.plazos.crear',
            'consola.plazos.editar',
            'consola.plazos.eliminar',
            
            // Gestion_Reportes
            'logs.visor.ver',
            'logs.dashboard.ver',
            
            // Gestion_Revisiones
            'revision.ver',
            'revision.gestionar',
            
            // Gestion_POA y Crear_POA (Planificación completa)
            'planificacion.planificar.ver',
            'planificacion.planificar.crear',
            'planificacion.planificar.editar',
            'planificacion.planificar.eliminar',
            'planificacion.actividades.ver',
            'planificacion.actividades.crear',
            'planificacion.actividades.editar',
            'planificacion.actividades.gestionar',
            'planificacion.actividades.eliminar',
            'planificacion.requisicion.ver',
            'planificacion.requisicion.crear',
            'planificacion.requisicion.editar',
            'planificacion.requisicion.eliminar',
            
            // Gestion_Seguimientos
            'planificacion.seguimiento.ver',
            'planificacion.seguimiento.crear',
            'planificacion.seguimiento.editar',
            'planificacion.seguimiento.eliminar',
            
            // Gestion_MIS_POAS
            'planificacion.consolidado.ver',
            'planificacion.consolidado.crear',
            'planificacion.consolidado.editar',
            'planificacion.consolidado.eliminar',
            
            // Gestion_Usuarios
            'configuracion.usuarios.ver',
            'configuracion.usuarios.crear',
            'configuracion.usuarios.editar',
            'configuracion.usuarios.eliminar',
            'configuracion.roles.ver',
            'configuracion.roles.crear',
            'configuracion.roles.editar',
            'configuracion.roles.eliminar',
            
            // Gestion_RRHH
            'configuracion.empleados.ver',
            'configuracion.empleados.crear',
            'configuracion.empleados.editar',
            'configuracion.empleados.eliminar',
            'configuracion.departamentos.ver',
            'configuracion.departamentos.crear',
            'configuracion.departamentos.editar',
            'configuracion.departamentos.eliminar',
            'configuracion.unidades-ejecutoras.ver',
            'configuracion.unidades-ejecutoras.crear',
            'configuracion.unidades-ejecutoras.editar',
            'configuracion.unidades-ejecutoras.eliminar',
        ];
        $direccion->syncPermissions($direccionPermissions);
        
        $this->command->info('✓ Roles y permisos creados exitosamente');
        $this->command->info('  - super_admin: ' . $superAdmin->permissions->count() . ' permisos');
        $this->command->info('  - admin: ' . $admin->permissions->count() . ' permisos');
        $this->command->info('  - planificador: ' . $planificador->permissions->count() . ' permisos');
        $this->command->info('  - direccion: ' . $direccion->permissions->count() . ' permisos');
        */
    }
}