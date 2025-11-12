<?php

use Rk\RoutingKit\Entities\RkRoute;

return [

    RkRoute::makeGroup('auth_group')
        ->setUrlMiddleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
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
                        ->setAccessPermission('configuracion.roles.ver')
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

                    RkRoute::make('roles.edit')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.roles.editar')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Rol\RoleForm')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('roles.edit'),

                    RkRoute::make('usuarios')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.usuarios.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Usuario\Usuarios')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('usuarios'),

                    RkRoute::make('empleados')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.empleados.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Empleado\Empleados')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('empleados'),

                    RkRoute::make('departamentos')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.departamentos.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Departamento\Departamentos')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('departamentos'),

                    RkRoute::make('unidades-ejecutoras')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.unidades-ejecutoras.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\UnidadEjecutora\UnidadesEjecutoras')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('unidades-ejecutoras'),

                    RkRoute::make('procesoscompras')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.procesoscompras.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\ProcesCompra\ProcesCompras')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('procesoscompras'),

                    RkRoute::make('cubs')
                        ->setParentId('configuracion')
                        ->setAccessPermission('configuracion.cubs.ver')
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
                        ->setAccessPermission('planificacion.planificar.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Planificar\Planificar')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('planificar'),

                    RkRoute::make('requerir')
                        ->setParentId('planificacion')
                        ->setAccessPermission('planificacion.requerir.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Requerir\Requerir')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('requerir'),

                    RkRoute::make('seguimiento')
                        ->setParentId('planificacion')
                        ->setAccessPermission('planificacion.seguimiento.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Seguimiento\Seguimiento')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('seguimiento'),

                    RkRoute::make('consolidado')
                        ->setParentId('planificacion')
                        ->setAccessPermission('planificacion.consolidado.ver')
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
                        ->setAccessPermission('consola.planestrategicoinstitucional.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Consola\PlanEstrategicoInstitucional')
                        ->setRoles(['admin_general'])
                        ->setItems([
                                    RkRoute::make('dimensiones')
                                ->setParentId('consola')
                                ->setAccessPermission('consola.dimensiones.ver')
                                ->setUrlMethod('get')
                                ->setUrlController('App\Livewire\Consola\Dimensiones\Dimension')
                                ->setRoles(['admin_general'])
                                ->setItems([
                                ])
                                ->setEndBlock('dimensiones'),
                        ])
                        ->setEndBlock('planestrategicoinstitucional'),

                    RkRoute::make('asignacionnacionalpresupuestaria')
                        ->setParentId('consola')
                        ->setAccessPermission('consola.asignacionnacionalpresupuestaria.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Consola\AsignacionPresuNacional')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('asignacionnacionalpresupuestaria'),

                    RkRoute::make('asignacionpresupuestaria')
                        ->setParentId('consola')
                        ->setAccessPermission('consola.asignacionpresupuestaria.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Consola\AsignacionPresupuestaria')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('asignacionpresupuestaria'),

                    RkRoute::make('techodeptos')
                        ->setParentId('consola')
                        ->setAccessPermission('consola.techodeptos.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\TechoDeptos\GestionTechoDeptos')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('techodeptos'),

                    RkRoute::make('techodeptos.detalle-estructura')
                        ->setParentId('consola')
                        ->setAccessPermission('consola.techodeptos.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\TechoDeptos\DetalleEstructura')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('techodeptos.detalle-estructura'),

                    RkRoute::make('techonacional')
                        ->setParentId('consola')
                        ->setAccessPermission('consola.techonacional.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\TechoUes\GestionTechoUeNacional')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('techonacional'),
                ])
                ->setEndBlock('consola'),

            RkRoute::makeGroup('logs_group')
                ->setParentId('auth_group')
                ->setItems([

                    RkRoute::make('logs')
                        ->setParentId('consola') // Puedes cambiarlo si tienes otro padre
                        ->setAccessPermission('logs.visor.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Http\Controllers\LogViewerController@index')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('logs'),

                    RkRoute::make('logsdashboard')
                        ->setParentId('consola')
                        ->setAccessPermission('logs.dashboard.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Http\Controllers\LogViewerController@dashboard')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('logsdashboard'),

                    RkRoute::make('sessions')
                        ->setParentId('consola')
                        ->setAccessPermission('logs.sessions.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Livewire\Admin\SessionManager') // Si es Livewire
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('sessions'),

                    RkRoute::make('logs.show')
                        ->setParentId('consola')
                        ->setAccessPermission('logs.visor.ver')
                        ->setUrlMethod('get')
                        ->setUrlController('App\Http\Controllers\LogViewerController@show')
                        ->setRoles(['admin_general'])
                        ->setItems([])
                        ->setEndBlock('logs.show'),

                    RkRoute::make('cleanup')
                        ->setParentId('consola')
                        ->setAccessPermission('logs.mantenimiento.limpiar')
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
