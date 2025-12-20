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
            //     ->setRoles(['admin_general'])
            //     ->setItems([])
            //     ->setEndBlock('dashboard'),

            RkRoute::make('trimestres')
                ->setParentId('auth_group')
                ->setAccessPermission('acceder-trimestres')
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Mes\Trimestres')
                ->setRoles(['admin_general'])
                ->setItems([])
                ->setEndBlock('trimestres'),

            RkRoute::make('tipoactividades')
                ->setParentId('auth_group')
                ->setAccessPermission('acceder-tipoactividades')
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Actividad\TipoActividades')
                ->setRoles(['admin_general'])
                ->setItems([])
                ->setEndBlock('tipoactividades'),

            RkRoute::make('tipo-acta-entregas')
                ->setParentId('auth_group')
                ->setAccessPermission('acceder-tipo-acta-entregas')
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Actas\TipoActaEntregas')
                ->setRoles(['admin_general'])
                ->setItems([])
                ->setEndBlock('tipo-acta-entregas'),

            RkRoute::make('unidad-medidas')
                ->setParentId('auth_group')
                ->setAccessPermission('acceder-unidad-medidas')
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Requisicion\UnidadMedidas')
                ->setRoles(['admin_general'])
                ->setItems([])
                ->setEndBlock('unidad-medidas'),

            RkRoute::make('categorias')
                ->setParentId('auth_group')
                ->setAccessPermission('acceder-categorias')
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Categoria\Categorias')
                ->setRoles(['admin_general'])
                ->setItems([])
                ->setEndBlock('categorias'),

            RkRoute::make('estados-ejecucion')
                ->setParentId('auth_group')
                ->setAccessPermission('acceder-estados-ejecucion')
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\EjecucionPresupuestaria\EstadosEjecucionPresupuestaria')
                ->setRoles(['admin_general'])
                ->setItems([])
                ->setEndBlock('estados-ejecucion'),

            RkRoute::make('estados-requisicion')
                ->setParentId('auth_group')
                ->setAccessPermission('acceder-estados-requisicion')
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Requisicion\EstadosRequisicion')
                ->setRoles(['admin_general'])
                ->setItems([])
                ->setEndBlock('estados-requisicion'),

            RkRoute::make('fuentes')
                ->setParentId('auth_group')
                ->setAccessPermission('acceder-fuentes')
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\GrupoGastos\Fuentes')
                ->setRoles(['admin_general'])
                ->setItems([])
                ->setEndBlock('fuentes'),

            RkRoute::make('grupo-gastos')
                ->setParentId('auth_group')
                ->setAccessPermission('acceder-grupo-gastos')
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\GrupoGastos\GrupoGastos')
                ->setRoles(['admin_general'])
                ->setItems([])
                ->setEndBlock('grupo-gastos'),

            RkRoute::make('instituciones')
                ->setParentId('auth_group')
                ->setAccessPermission('acceder-instituciones')
                ->setUrlMethod('get')
                ->setUrlController('App\Livewire\Institucion\Instituciones')
                ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('roles'),

                    RkRoute::make('roles.create')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.roles.crear')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Rol\RoleForm')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('roles.create'),

                   /* RkRoute::make('roles.edit')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.roles.editar')
                        ->setUrlMethod('get')
                        ->setUrlPattern('roles/{roleId}/editar')
                        ->setUrlController('App\Livewire\Rol\RoleForm')
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('procesoscompras'),

                    RkRoute::make('recursos')
                        ->setParentId('configuracion')
                        ->setAccessPermission('acceso-configuracion')
                        ->setPermissions([
                            'recursos.ver',
                            'recursos.crear',
                            'recursos.editar',
                            'recursos.eliminar',
                            'acceso-configuracion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Tarea\TareasHistorico')
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
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
                            'acceso-planificacion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Actividad\Actividades')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('actividades'),

                    RkRoute::make('gestionar-actividad')
                        ->setParentId('planificacion')
                        ->setAccessPermission('acceso-planificacion')
                        ->setPermissions([
                            'planificacion.actividades.ver',
                            'planificacion.actividades.editar',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Actividad\GestionarActividad')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('gestionar-actividad'),
                    
                        RkRoute::make('revisiones')
                        ->setParentId('planificacion')
                        ->setAccessPermission('acceso-planificacion')
                        ->setPermissions([
                            'revision.ver',
                            'revision.gestionar'
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Revision\Revisiones')
                        ->setRoles(['admin_general'])
                        ->setItems([
                            RkRoute::make('revision-actividades')
                                ->setParentId('revisiones')
                                ->setAccessPermission('revision.gestionar')
                                ->setUrlMethod('get')
                                ->setUrlController('App\Livewire\Revision\ActividadesRevision')
                                ->setRoles(['admin_general'])
                                ->setItems([])
                                ->setEndBlock('revision-actividades'),
                            RkRoute::make('review-actividad-detalle')
                                        ->setParentId('revisiones')
                                        ->setAccessPermission('revision.gestionar')
                                        ->setUrlMethod('get')
                                        ->setUrlController('App\Livewire\Revision\ReviewActividadDetalle')
                                        ->setRoles(['admin_general'])
                                        ->setItems([])
                                        ->setEndBlock('review-actividad-detalle'),
                        ])
                        ->setEndBlock('revisiones'),

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
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('requisicion'),

                    RkRoute::make('seguimiento')
                        ->setParentId('planificacion')
                        ->setAccessPermission('acceso-planificacion')
                        ->setPermissions([
                            'planificacion.seguimiento.ver',
                            'planificacion.seguimiento.crear',
                            'planificacion.seguimiento.editar',
                            'planificacion.seguimiento.eliminar',
                            'acceso-planificacion',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Requisicion\Requisicion')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('seguimiento'),

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
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
                        ->setItems([
                                    RkRoute::make('dimensiones')
                                ->setParentId('consola')
                                ->setAccessPermission('consola.dimensiones.ver')
                                ->setUrlMethod('get')
                                ->setUrlController('App\Livewire\Consola\Pei\Dimensiones\Dimension')
                                ->setRoles(['admin_general'])
                                ->setItems([
                                        RkRoute::make('objetivos')
                                    ->setParentId('consola')
                                    ->setAccessPermission('consola.objetivos.ver')
                                    ->setUrlMethod('get')
                                    ->setUrlController('App\Livewire\Consola\Pei\Objetivos\Objetivo')
                                    ->setRoles(['admin_general'])
                                    ->setItems([
                                         RkRoute::make('areas')
                                    ->setParentId('consola')
                                    ->setAccessPermission('consola.areas.ver')
                                    ->setUrlMethod('get')
                                    ->setUrlController('App\Livewire\Consola\Pei\Areas\Area')
                                    ->setRoles(['admin_general'])
                                    ->setItems([
                                        RkRoute::make('resultados')
                                    ->setParentId('consola')
                                    ->setAccessPermission('consola.resultados.ver')
                                    ->setUrlMethod('get')
                                    ->setUrlController('App\Livewire\Consola\Pei\Resultados\Resultado')
                                    ->setRoles(['admin_general'])
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
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Consola\AsignacionPresuNacional')
                        ->setRoles(['admin_general'])
                        ->setItems([
                             RkRoute::make('analysis-techo-ue')
                                ->setParentId('consola')
                                ->setAccessPermission('acceso-consola')
                                ->setPermissions([
                                    'consola.techonacional.ver',
                                    'acceso-consola',
                                ])
                                ->setUrlMethod('get')
                                ->setUrlPattern('techonacional/{idPoa}/analysis/{idUE}')
                                ->setUrlController('App\Livewire\TechoUes\AnalysisTechoUe')
                                ->setRoles(['admin_general'])
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
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Consola\AsignacionPresupuestaria')
                        ->setRoles(['admin_general'])
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
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\TechoDeptos\GestionTechoDeptos')
                        ->setRoles(['admin_general'])
                        ->setItems([])
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
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('techonacional'),

                    RkRoute::make('plazos')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'consola.plazos.gestionar',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Plazos\GestionPlazos')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('plazos'),

                    RkRoute::make('plazos-poa')
                        ->setParentId('consola')
                        ->setAccessPermission('acceso-consola')
                        ->setPermissions([
                            'consola.plazos.gestionar',
                            'acceso-consola',
                        ])
                        ->setUrlMethod('get')
                        ->setUrlPattern('plazos-poa/{idPoa}')
                        ->setUrlController('App\Livewire\Plazos\GestionPlazosPoa')
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
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
                        ->setUrlController('App\Http\Controllers\LogViewerController@show')
                        ->setRoles(['admin_general'])
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
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('cleanup'),

                ])
                ->setEndBlock('logs_group'),
        ])
        ->setEndBlock('auth_group'),
];
