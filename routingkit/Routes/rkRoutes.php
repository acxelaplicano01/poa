<?php

use Rk\RoutingKit\Entities\RkRoute;

return [

    RkRoute::makeGroup('auth_group')
        ->setUrlMiddleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
            'check.empleado',
        ])
        ->setItems([
            

            // RkRoute::make('dashboard')
            //     ->setParentId('auth_group')
            //     ->setAccessPermission('acceder-dashboard')
            //     ->setUrlMethod('get')
            //     ->setUrlController('App\Http\Controllers\DashboardController')
            //     ->SetRol()
            //     ->setItems([])
            //     ->setEndBlock('dashboard'),

            RkRoute::make('trimestres')
                ->setParentId('auth_group')
                ->setAccessPermission('acceso-configuracion')
                ->setPermissions([
                    'configuracion.trimestres.ver', 
                    'configuracion.trimestres.editar', 
                    'configuracion.trimestres.crear', 
                    'configuracion.trimestres.eliminar', 
                    'acceso-configuracion'
                ])
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Mes\Trimestres')
                ->setRoles(['super_admin', 'admin'])
                ->setItems([])
                ->setEndBlock('trimestres'),

            RkRoute::make('tipoactividades')
                ->setParentId('auth_group')
                ->setAccessPermission('acceso-configuracion')
                ->setPermissions([
                    'configuracion.tipoactividades.ver', 
                    'configuracion.tipoactividades.editar', 
                    'configuracion.tipoactividades.crear', 
                    'configuracion.tipoactividades.eliminar', 
                    'acceso-configuracion'
                ])
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Actividad\TipoActividades')
                ->setRoles(['super_admin', 'admin'])
                ->setItems([])
                ->setEndBlock('tipoactividades'),

            RkRoute::make('tipo-acta-entregas')
                ->setParentId('auth_group')
                ->setAccessPermission('acceso-configuracion')
                ->setPermissions([
                    'configuracion.tipoactaentregas.ver', 
                    'configuracion.tipoactaentregas.editar', 
                    'configuracion.tipoactaentregas.crear', 
                    'configuracion.tipoactaentregas.eliminar', 
                    'acceso-configuracion'
                ])
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Actas\TipoActaEntregas')
                ->setRoles(['super_admin', 'admin'])
                ->setItems([])
                ->setEndBlock('tipo-acta-entregas'),

            RkRoute::make('unidad-medidas')
                ->setParentId('auth_group')
                ->setAccessPermission('acceso-configuracion')
                ->setPermissions([
                    'configuracion.unidadmedidas.ver', 
                    'configuracion.unidadmedidas.editar', 
                    'configuracion.unidadmedidas.crear', 
                    'configuracion.unidadmedidas.eliminar', 
                    'acceso-configuracion'
                ])
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Requisicion\UnidadMedidas')
                ->setRoles(['super_admin', 'admin'])
                ->setItems([])
                ->setEndBlock('unidad-medidas'),

            RkRoute::make('categorias')
                ->setParentId('auth_group')
                ->setAccessPermission('acceso-configuracion')
                ->setPermissions([
                    'configuracion.categorias.ver', 
                    'configuracion.categorias.editar', 
                    'configuracion.categorias.crear', 
                    'configuracion.categorias.eliminar', 
                    'acceso-configuracion'
                ])
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Categoria\Categorias')
                ->setRoles(['super_admin', 'admin'])
                ->setItems([])
                ->setEndBlock('categorias'),

            RkRoute::make('estados-ejecucion')
                ->setParentId('auth_group')
                ->setAccessPermission('acceso-configuracion')
                ->setPermissions([
                    'configuracion.estadosejecucion.ver', 
                    'configuracion.estadosejecucion.editar', 
                    'configuracion.estadosejecucion.crear', 
                    'configuracion.estadosejecucion.eliminar', 
                    'acceso-configuracion'
                ])
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\EjecucionPresupuestaria\EstadosEjecucionPresupuestaria')
                ->setRoles(['super_admin', 'admin'])
                ->setItems([])
                ->setEndBlock('estados-ejecucion'),

            RkRoute::make('estados-requisicion')
                ->setParentId('auth_group')
                ->setAccessPermission('acceso-configuracion')
                ->setPermissions([
                    'configuracion.estadosrequisicion.ver', 
                    'configuracion.estadosrequisicion.editar', 
                    'configuracion.estadosrequisicion.crear', 
                    'configuracion.estadosrequisicion.eliminar', 
                    'acceso-configuracion'
                ])
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Requisicion\EstadosRequisicion')
                ->setRoles(['super_admin', 'admin'])
                ->setItems([])
                ->setEndBlock('estados-requisicion'),

            RkRoute::make('fuentes')
                ->setParentId('auth_group')
                ->setAccessPermission('acceso-configuracion')
                ->setPermissions([
                    'configuracion.fuentes.ver', 
                    'configuracion.fuentes.editar', 
                    'configuracion.fuentes.crear', 
                    'configuracion.fuentes.eliminar', 
                    'acceso-configuracion'
                ])
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\GrupoGastos\Fuentes')
                ->setRoles(['super_admin', 'admin'])
                ->setItems([])
                ->setEndBlock('fuentes'),

            RkRoute::make('grupo-gastos')
                ->setParentId('auth_group')
                ->setAccessPermission('acceso-configuracion')
                ->setPermissions([
                    'configuracion.grupogastos.ver', 
                    'configuracion.grupogastos.editar', 
                    'configuracion.grupogastos.crear', 
                    'configuracion.grupogastos.eliminar', 
                    'acceso-configuracion'
                ])
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\GrupoGastos\GrupoGastos')
                ->setRoles(['super_admin', 'admin'])
                ->setItems([])
                ->setEndBlock('grupo-gastos'),

            RkRoute::make('instituciones')
                ->setParentId('auth_group')
                ->setAccessPermission('acceso-configuracion')
                ->setPermissions([
                    'configuracion.instituciones.ver', 
                    'configuracion.instituciones.editar', 
                    'configuracion.instituciones.crear', 
                    'configuracion.instituciones.eliminar', 
                    'acceso-configuracion'
                ])
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Institucion\Instituciones')
                ->setRoles(['super_admin', 'admin'])
                ->setItems([])
                ->setEndBlock('instituciones'),

            RkRoute::makeGroup('configuracion')
                ->setParentId('auth_group')
                ->setItems([

                    RkRoute::make('roles')
                        ->setParentId('configuracion')
                        ->setAccessPermission('acceso-configuracion')
                        ->setPermissions([
                            'configuracion.roles.ver', 
                            'configuracion.roles.editar', 
                            'configuracion.roles.crear', 
                            'configuracion.roles.eliminar', 
                            'acceso-configuracion'
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Rol\Roles')
                        ->setRoles(['super_admin', 'admin', 'direccion'])
                        ->setItems([])
                        ->setEndBlock('roles'),

                    RkRoute::make('roles.create')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.roles.crear')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Rol\RoleForm')
                        ->setRoles(['super_admin', 'admin', 'direccion'])
                        ->setItems([])
                        ->setEndBlock('roles.create'),

                   /* RkRoute::make('roles.edit')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.roles.editar')
                        ->setUrlMethod('get')
                        ->setUrlPattern('roles/{roleId}/editar')
                        ->setUrlController('App\Livewire\Rol\RoleForm')
                        ->setRol()
                        ->setItems([])
                        ->setEndBlock('roles.edit'),*/

                    RkRoute::make('usuarios')
                        ->setParentId('configuracion')
                        ->setAccessPermission('acceso-configuracion')
                        ->setPermissions([
                            'configuracion.usuarios.ver',
                            'configuracion.usuarios.crear',
                            'configuracion.usuarios.editar',
                            'configuracion.usuarios.eliminar',
                            'acceso-configuracion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Usuario\Usuarios')
                        ->setRoles(['super_admin', 'admin', 'direccion'])
                        ->setItems([])
                        ->setEndBlock('usuarios'),

                    RkRoute::make('empleados')
                        ->setParentId('configuracion')
                        ->setAccessPermission('acceso-configuracion')
                        ->setPermissions([
                            'configuracion.empleados.ver',
                            'configuracion.empleados.crear',
                            'configuracion.empleados.editar',
                            'configuracion.empleados.eliminar',
                            'acceso-configuracion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Empleado\Empleados')
                        ->setRoles(['super_admin', 'admin', 'direccion'])
                        ->setItems([])
                        ->setEndBlock('empleados'),

                    RkRoute::make('departamentos')
                        ->setParentId('configuracion')
                        ->setAccessPermission('acceso-configuracion')
                        ->setPermissions([
                            'configuracion.departamentos.ver',
                            'configuracion.departamentos.crear',
                            'configuracion.departamentos.editar',
                            'configuracion.departamentos.eliminar',
                            'acceso-configuracion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Departamento\Departamentos')
                        ->setRoles(['super_admin', 'admin', 'direccion'])
                        ->setItems([])
                        ->setEndBlock('departamentos'),

                    RkRoute::make('unidades-ejecutoras')
                        ->setParentId('configuracion')
                        ->setAccessPermission('acceso-configuracion')
                        ->setPermissions([
                            'configuracion.unidades-ejecutoras.ver',
                            'configuracion.unidades-ejecutoras.crear',
                            'configuracion.unidades-ejecutoras.editar',
                            'configuracion.unidades-ejecutoras.eliminar',
                            'acceso-configuracion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\UnidadEjecutora\UnidadesEjecutoras')
                        ->setRoles(['super_admin', 'admin', 'direccion'])
                        ->setItems([])
                        ->setEndBlock('unidades-ejecutoras'),

                    RkRoute::make('procesoscompras')
                        ->setParentId('configuracion')
                        ->setAccessPermission('acceso-configuracion')
                        ->setPermissions([
                            'configuracion.procesoscompras.ver',
                            'configuracion.procesoscompras.crear',
                            'configuracion.procesoscompras.editar',
                            'configuracion.procesoscompras.eliminar',
                            'acceso-configuracion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\ProcesCompra\ProcesCompras')
                        ->setRoles(['super_admin', 'admin'])
                        ->setItems([])
                        ->setEndBlock('procesoscompras'),

                    RkRoute::make('recursos')
                        ->setParentId('configuracion')
                        ->setAccessPermission('acceso-configuracion')
                        ->setPermissions([
                            'configuracion.recursos.ver',
                            'configuracion.recursos.crear',
                            'configuracion.recursos.editar',
                            'configuracion.recursos.eliminar',
                            'acceso-configuracion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Tarea\TareasHistorico')
                        ->setRoles(['super_admin', 'admin'])
                        ->setItems([])
                        ->setEndBlock('recursos'),

                    RkRoute::make('cubs')
                        ->setParentId('configuracion')
                        ->setAccessPermission('acceso-configuracion')
                        ->setPermissions([
                            'configuracion.cubs.ver',
                            'configuracion.cubs.crear',
                            'configuracion.cubs.editar',
                            'configuracion.cubs.eliminar',
                            'acceso-configuracion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Cub\Cubs')
                        ->setRoles(['super_admin', 'admin'])
                        ->setItems([])
                        ->setEndBlock('cubs'),
                ])
                ->setEndBlock('configuracion'),

            RkRoute::makeGroup('planificacion')
                ->setParentId('auth_group')
                ->setItems([

                    RkRoute::make('planificar')
                        ->setParentId('planificacion')
                        ->setAccessPermission('acceso-planificacion')
                        ->setPermissions([
                            'planificacion.planificar.ver',
                            'planificacion.planificar.crear',
                            'planificacion.planificar.editar',
                            'planificacion.planificar.eliminar',
                            'acceso-planificacion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Planificar\Planificar')
                        ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                        ->setItems([])
                        ->setEndBlock('planificar'),

                    RkRoute::make('actividades')
                        ->setParentId('planificacion')
                        ->setAccessPermission('acceso-planificacion')
                        ->setPermissions([
                            'planificacion.actividades.ver',
                            'planificacion.actividades.crear',
                            'planificacion.actividades.editar',
                            'planificacion.actividades.eliminar',
                            'planificacion.actividades.gestionar',
                            'acceso-planificacion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Actividad\Actividades')
                        ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                        ->setItems([])
                        ->setEndBlock('actividades'),

                    RkRoute::make('gestionar-actividad')
                        ->setParentId('planificacion')
                        ->setAccessPermission('acceso-planificacion')
                        ->setPermissions([
                            'planificacion.actividades.ver',
                            'planificacion.actividades.crear',
                            'planificacion.actividades.editar',
                            'planificacion.actividades.eliminar',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Actividad\GestionarActividad')
                        ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                        ->setItems([])
                        ->setEndBlock('gestionar-actividad'),
                    
                        RkRoute::make('revisiones')
                        ->setParentId('planificacion')
                        ->setAccessPermission('revision.gestionar')
                        ->setPermissions([
                            'revision.ver',
                            'revision.gestionar'
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Revision\Revisiones')
                        ->setRoles(['super_admin', 'admin', 'direccion'])
                        ->setItems([
                            RkRoute::make('revision-actividades')
                                ->setParentId('revisiones')
                                ->setAccessPermission('revision.gestionar')
                                ->setUrlMethod('get')
                                ->setUrlController('App\Livewire\Revision\ActividadesRevision')
                                ->setRoles(['super_admin', 'admin', 'direccion'])
                                ->setItems([])
                                ->setEndBlock('revision-actividades'),
                            RkRoute::make('review-actividad-detalle')
                                        ->setParentId('revisiones')
                                        ->setAccessPermission('revision.gestionar')
                                        ->setUrlMethod('get')
                                        ->setUrlController('App\Livewire\Revision\ReviewActividadDetalle')
                                        ->setRoles(['super_admin', 'admin', 'direccion'])
                                        ->setItems([])
                                        ->setEndBlock('review-actividad-detalle'),
                        ])
                        ->setEndBlock('revisiones'),
/*
                    RkRoute::make('requisicion')
                        ->setParentId('planificacion')
                        ->setAccessPermission('acceso-planificacion')
                        ->setPermissions([
                            'planificacion.requisicion.ver',
                            'planificacion.requisicion.crear',
                            'planificacion.requisicion.editar',
                            'planificacion.requisicion.eliminar',
                            'acceso-planificacion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Requisicion\Requisicion')
                        ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                        ->setItems([])
                        ->setEndBlock('requisicion'), */

                RkRoute::makeGroup('requisiciones')
                    ->setParentId('planificacion')
                    ->setAccessPermission('acceso-planificacion')
                    ->setPermissions([
                        'requisiciones.ver',
                        'requisiciones.crear',
                        'requisiciones.editar',
                        'requisiciones.eliminar',
                        'acceso-planificacion',
                    ])
                    ->setUrlMethod('get')
                    ->setUrlController('App\Livewire\Requisicion\SeguimientoRequisicion')
                    ->setRoles(['super_admin'])
                    ->setItems([
                        RkRoute::make('mis-requisiciones')
                            ->setParentId('requisiciones')
                            ->setAccessPermission('acceso-planificacion')
                            ->setUrlMethod('get')
                            ->setUrlController('App\Livewire\Requisicion\SeguimientoRequisicion')
                            ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                            ->setItems([])
                            ->setEndBlock('mis-requisiciones'),

                        RkRoute::make('requisicion')
                            ->setParentId('requisiciones')
                            ->setAccessPermission('acceso-planificacion')
                            ->setPermissions([
                                'planificacion.requisicion.ver',
                                'planificacion.requisicion.crear',
                                'planificacion.requisicion.editar',
                                'planificacion.requisicion.eliminar',
                                'acceso-planificacion',
                            ])
                            ->setUrlMethod('get')
                            ->setUrlController('App\Livewire\Requisicion\Requisicion')
                            ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                            ->setItems([])
                            ->setEndBlock('requisicion'),

                        RkRoute::make('requisicion/{correlativo}/pdf')
                            ->setParentId('requisiciones')
                            ->setAccessPermission('acceso-planificacion')
                            ->setUrlMethod('get')
                            ->setUrlController('App\\Http\\Controllers\\RequisicionController@descargarPdf')
                            ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                            ->setItems([])
                            ->setEndBlock('requisicion-pdf'),
                    ])
                    ->setEndBlock('requisiciones'),

                    RkRoute::make('administrar-requisiciones')
                        ->setParentId('planificacion')
                        ->setAccessPermission('administrar.requisiciones.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Requisicion\AdministrarRequisiciones')
                        ->setRoles(['super_admin', 'admin'])
                        ->setItems([])
                        ->setEndBlock('administrar-requisiciones'),

                    RkRoute::make('entregarecursos')
                        ->setParentId('planificacion')
                        ->setAccessPermission('acceder-entrega-recursos')
                        ->setUrlMethod('get')
                        ->setUrl('entregarecursos/{requisicionId}')
                        ->setUrlController('App\Livewire\Requisicion\EntregaRecursos')
                        ->setRoles(['super_admin', 'admin'])
                        ->setItems([])
                        ->setEndBlock('entregarecursos'),
                    
                    RkRoute::make('acta-entrega-pdf')
                        ->setParentId('planificacion')
                        ->setAccessPermission('acceder-entrega-recursos')
                        ->setUrlMethod('get')
                        ->setUrl('acta-entrega/{requisicionId}/descargar')
                        ->setUrlController('App\\Http\\Controllers\\ActaEntregaController@descargarPdf')
                        ->setRoles(['super_admin', 'admin'])
                        ->setItems([])
                        ->setEndBlock('acta-entrega-pdf'),

                    RkRoute::make('consolidado')
                        ->setParentId('planificacion')
                        ->setAccessPermission('acceso-planificacion')
                        ->setPermissions([
                            'planificacion.consolidado.ver',
                            'planificacion.consolidado.crear',
                            'planificacion.consolidado.editar',
                            'planificacion.consolidado.eliminar',
                            'acceso-planificacion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Consolidado\Consolidado')
                        ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                        ->setItems([])
                        ->setEndBlock('consolidado'),
                ])
                ->setEndBlock('planificacion'),

            RkRoute::makeGroup('consola')
                ->setParentId('auth_group')
                ->setItems([

                    RkRoute::make('planestrategicoinstitucional')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'consola.planestrategicoinstitucional.ver',
                            'consola.planestrategicoinstitucional.crear',
                            'consola.planestrategicoinstitucional.editar',
                            'consola.planestrategicoinstitucional.eliminar',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Consola\PlanEstrategicoInstitucional')
                        ->setRoles(['super_admin', 'direccion'])
                        ->setItems([
                                    RkRoute::make('dimensiones')
                                ->setParentId('consola')
                                ->setAccessPermission('acceso-consola')
                                ->setPermissions([
                                    'consola.dimensiones.ver', 
                                    'consola.dimensiones.editar', 
                                    'consola.dimensiones.crear', 
                                    'consola.dimensiones.eliminar', 
                                    'acceso-consola'
                                ])
                                ->setUrlMethod('get')
                                ->setUrlController('App\Livewire\Consola\Pei\Dimensiones\Dimension')
                                ->setRoles(['super_admin', 'direccion'])
                                ->setItems([
                                        RkRoute::make('objetivos')
                                            ->setParentId('consola')
                                            ->setAccessPermission('acceso-consola')
                                            ->setPermissions([
                                                'consola.objetivos.ver', 
                                                'consola.objetivos.editar', 
                                                'consola.objetivos.crear', 
                                                'consola.objetivos.eliminar', 
                                                'acceso-consola'
                                            ])
                                            ->setUrlMethod('get')
                                            ->setUrlController('App\Livewire\Consola\Pei\Objetivos\Objetivo')
                                            ->setRoles(['super_admin', 'direccion'])
                                            ->setItems([
                                                RkRoute::make('areas')
                                                    ->setParentId('consola')
                                                    ->setAccessPermission('acceso-consola')
                                                    ->setPermissions([
                                                        'consola.areas.ver', 
                                                        'consola.areas.editar', 
                                                        'consola.areas.crear', 
                                                        'consola.areas.eliminar', 
                                                        'acceso-consola'
                                                    ])
                                                    ->setUrlMethod('get')
                                                    ->setUrlController('App\Livewire\Consola\Pei\Areas\Area')
                                                    ->setRoles(['super_admin', 'direccion'])
                                                    ->setItems([
                                                RkRoute::make('resultados')
                                                    ->setParentId('consola')
                                                    ->setAccessPermission('acceso-consola')
                                                    ->setPermissions([
                                                        'consola.tipoactividades.ver', 
                                                        'consola.tipoactividades.editar', 
                                                        'consola.tipoactividades.crear', 
                                                        'consola.tipoactividades.eliminar', 
                                                        'acceso-consola'
                                                    ])
                                                    ->setUrlMethod('get')
                                                    ->setUrlController('App\Livewire\Consola\Pei\Resultados\Resultado')
                                                    ->setRoles(['super_admin', 'direccion'])
                                                    ->setItems([
                                                    ])
                                                    ->setEndBlock('resultados'),
                                            ])
                                            ->setEndBlock('areas'),
                                            ])
                                            ->setEndBlock('objetivos'),
                                ])
                                ->setEndBlock('planestrategicoinstitucional'),
                        ])
                        ->setEndBlock('planestrategicoinstitucional'),

                    RkRoute::make('asignacionnacionalpresupuestaria')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'consola.asignacionnacionalpresupuestaria.ver',
                            'consola.asignacionnacionalpresupuestaria.crear',
                            'consola.asignacionnacionalpresupuestaria.editar',
                            'consola.asignacionnacionalpresupuestaria.eliminar',
                            'consola.asignacionnacionalpresupuestaria.asignar',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Consola\AsignacionPresuNacional')
                        ->setRoles(['super_admin', 'direccion'])
                        ->setItems([
                             RkRoute::make('analysis-techo-ue')
                                ->setParentId('consola')
                                ->setAccessPermission('acceso-consola')
                                ->setPermissions([
                                    'consola.techonacional.ver',
                                    'acceso-consola',
                                ])
                                ->setUrlMethod('get')
                                ->setUrl('techonacional/{idPoa}/analysis/{idUE}')
                                ->setUrlController('App\Livewire\TechoUes\AnalysisTechoUe')
                                ->setRoles(['super_admin', 'direccion'])
                                ->setItems([])
                                ->setEndBlock('analysis-techo-ue'),
                        ])
                        ->setEndBlock('asignacionnacionalpresupuestaria'),

                    RkRoute::make('asignacionpresupuestaria')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'consola.asignacionpresupuestaria.ver',
                            'consola.asignacionpresupuestaria.crear',
                            'consola.asignacionpresupuestaria.editar',
                            'consola.asignacionpresupuestaria.eliminar',
                            'consola.asignacionpresupuestaria.asignar',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Consola\AsignacionPresupuestaria')
                        ->setRoles(['super_admin', 'direccion'])
                        ->setItems([])
                        ->setEndBlock('asignacionpresupuestaria'),

                    RkRoute::make('techodeptos')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'consola.techodeptos.ver',
                            'consola.techodeptos.crear',
                            'consola.techodeptos.editar',
                            'consola.techodeptos.eliminar',
                            'consola.techodeptos.asignar',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\TechoDeptos\GestionTechoDeptos')
                        ->setRoles(['super_admin', 'direccion'])
                        ->setItems([
                            RkRoute::make('analysis-techo-depto')
                                ->setParentId('consola')
                                ->setAccessPermission('acceso-consola')
                                ->setPermissions([
                                    'consola.techodeptos.ver',
                                    'acceso-consola',
                                ])
                                ->setUrlMethod('get')
                                ->setUrl('techodeptos/{idPoa}/{idUE}/analysis/{idDepartamento}')
                                ->setUrlController('App\Livewire\TechoDeptos\AnalysisTechoDepto')
                                ->setRoles(['super_admin', 'direccion'])
                                ->setItems([])
                                ->setEndBlock('analysis-techo-depto'),
                        ])
                        ->setEndBlock('techodeptos'),

                    RkRoute::make('techodeptos.detalle-estructura')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'consola.techodeptos.ver',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\TechoDeptos\DetalleEstructura')
                        ->setRoles(['super_admin', 'direccion'])
                        ->setItems([])
                        ->setEndBlock('techodeptos.detalle-estructura'),

                    RkRoute::make('techonacional')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'consola.techonacional.ver',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\TechoUes\GestionTechoUeNacional')
                        ->setRoles(['super_admin', 'direccion'])
                        ->setItems([])
                        ->setEndBlock('techonacional'),

                    RkRoute::make('plazos-poa')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'consola.plazos.gestionar',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrl('plazos-poa/{idPoa}')
                        ->setUrlController('App\Livewire\Plazos\GestionPlazosPoa')
                        ->setRoles(['super_admin', 'direccion'])
                        ->setItems([])
                        ->setEndBlock('plazos-poa'),


                ])
                ->setEndBlock('consola'),

            RkRoute::makeGroup('logs_group')
                ->setParentId('auth_group')
                ->setItems([

                    RkRoute::make('logs')
                        ->setParentId('consola') // Puedes cambiarlo si tienes otro padre
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'logs.visor.ver',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Http\Controllers\LogViewerController@index')
                        ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                        ->setItems([])
                        ->setEndBlock('logs'),

                    RkRoute::make('logsdashboard')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'logs.dashboard.ver',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Http\Controllers\LogViewerController@dashboard')
                        ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                        ->setItems([])
                        ->setEndBlock('logsdashboard'),

                    RkRoute::make('sessions')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'logs.sessions.ver',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Admin\SessionManager') // Si es Livewire
                        ->setRoles(['super_admin', 'admin'])
                        ->setItems([])
                        ->setEndBlock('sessions'),

                    RkRoute::make('logs.show')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'logs.visor.ver',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrl('logs/{log}')
                        ->setUrlController('App\Http\Controllers\LogViewerController@show')
                        ->setRoles(['super_admin', 'admin', 'direccion', 'planificador'])
                        ->setItems([])
                        ->setEndBlock('logs.show'),

                    RkRoute::make('cleanup')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'logs.mantenimiento.limpiar',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('post')
                        ->setUrlController('App\Http\Controllers\LogViewerController@cleanup')
                        ->setRoles(['super_admin'])
                        ->setItems([])
                        ->setEndBlock('cleanup'),

                ])
                ->setEndBlock('logs_group'),
        ])
        ->setEndBlock('auth_group'),
];
