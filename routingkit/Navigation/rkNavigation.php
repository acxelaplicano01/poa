<?php

use Rk\RoutingKit\Entities\RkNavigation;

return [

    RkNavigation::makeGroup('dashboard_group')
        ->setDescription('Panel principal del sistema')
        ->setLabel('Inicio')
        ->setHeroIcon('home')
        ->setItems([

            RkNavigation::makeSimple('dashboard')
                ->setParentId('dashboard_group')
                ->setUrl('/dashboard')
                ->setDescription('Administra estadisticas y supervisa los movimientos del sistema')
                ->setLabel('Panel Principal')
                ->setHeroIcon('home')
                ->setItems([])
                ->setEndBlock('dashboard'),
        ])
        ->setEndBlock('dashboard_group'),

    RkNavigation::makeGroup('planificacion')
        ->setLabel('Planificación')
        ->setHeroIcon('calendar')
        ->setItems([

            RkNavigation::make('planificar')
                ->setParentId('planificacion')
                ->setDescription('Visualiza tus planificaciones')
                ->setLabel('Mis planificaciones')
                ->setHeroIcon('document-text')
                ->setItems([
                    RkNavigation::make('actividades')
                        ->setDescription('Ver y gestionar mis actividades planificadas')
                        ->setLabel('Mis actividades')
                        ->setHeroIcon('folder-open')
                        ->setEndBlock('actividades'),
                ])
                ->setEndBlock('planificar'),

            RkNavigation::make('requerir')
                ->setParentId('planificacion')
                ->setDescription('Crear o gestionar requerimientos')
                ->setLabel('Requerir')
                ->setHeroIcon('clipboard-document')
                ->setItems([])
                ->setEndBlock('requerir'),

            RkNavigation::make('seguimiento')
                ->setParentId('planificacion')
                ->setDescription('Seguimiento de planificaciones')
                ->setLabel('Dar seguimiento')
                ->setHeroIcon('eye')
                ->setItems([])
                ->setEndBlock('seguimiento'),

            RkNavigation::make('consolidado')
                ->setParentId('planificacion')
                ->setDescription('Genera reportes consolidados')
                ->setLabel('Consolidado')
                ->setHeroIcon('chart-bar-square')
                ->setItems([])
                ->setEndBlock('consolidado'),
        ])
        ->setEndBlock('planificacion'),

    RkNavigation::makeGroup('configuracion')
        ->setLabel('Configuración')
        ->setHeroIcon('cog-6-tooth')
        ->setItems([

            RkNavigation::makeGroup('usuarios-accesos')
                ->setParentId('configuracion')
                ->setLabel('Usuarios')
                ->setHeroIcon('user-group')
                ->setItems([

                    RkNavigation::make('roles')
                        ->setParentId('usuarios-accesos')
                        ->setDescription('Gestiona roles de usuario')
                        ->setLabel('Roles')
                        ->setHeroIcon('shield-exclamation')
                        ->setItems([])
                        ->setEndBlock('roles'),

                    RkNavigation::make('usuarios')
                        ->setParentId('usuarios-accesos')
                        ->setDescription('Administración de usuarios')
                        ->setLabel('Usuarios')
                        ->setHeroIcon('users')
                        ->setItems([])
                        ->setEndBlock('usuarios'),

                    RkNavigation::make('empleados')
                        ->setParentId('usuarios-accesos')
                        ->setDescription('Gestión de empleados')
                        ->setLabel('Empleados')
                        ->setHeroIcon('identification')
                        ->setItems([])
                        ->setEndBlock('empleados'),
                ])
                ->setEndBlock('usuarios-accesos'),

            RkNavigation::makeGroup('estructura-organizacional')
                ->setParentId('configuracion')
                ->setLabel('Organización')
                ->setHeroIcon('building-office')
                ->setItems([

                    RkNavigation::make('departamentos')
                        ->setParentId('estructura-organizacional')
                        ->setDescription('Administración de departamentos')
                        ->setLabel('Departamentos')
                        ->setHeroIcon('building-office')
                        ->setItems([])
                        ->setEndBlock('departamentos'),

                    RkNavigation::make('unidades-ejecutoras')
                        ->setParentId('estructura-organizacional')
                        ->setDescription('Gestión de unidades ejecutoras')
                        ->setLabel('Unidades Ejecutoras')
                        ->setHeroIcon('building-library')
                        ->setItems([])
                        ->setEndBlock('unidades-ejecutoras'),

                    RkNavigation::make('instituciones')
                        ->setParentId('estructura-organizacional')
                        ->setDescription('Gestión de instituciones')
                        ->setLabel('Instituciones')
                        ->setHeroIcon('building-office-2')
                        ->setItems([])
                        ->setEndBlock('instituciones'),

                    RkNavigation::make('cubs')
                        ->setParentId('estructura-organizacional')
                        ->setDescription('Administración de cubs')
                        ->setLabel('Cubs')
                        ->setHeroIcon('cube')
                        ->setItems([])
                        ->setEndBlock('cubs'),
                ])
                ->setEndBlock('estructura-organizacional'),

            RkNavigation::makeGroup('config-presupuestaria')
                ->setParentId('configuracion')
                ->setLabel('Presupuesto')
                ->setHeroIcon('banknotes')
                ->setItems([

                    RkNavigation::make('fuentes')
                        ->setParentId('config-presupuestaria')
                        ->setDescription('Gestión de fuentes de financiamiento')
                        ->setLabel('Fuentes')
                        ->setHeroIcon('currency-dollar')
                        ->setItems([])
                        ->setEndBlock('fuentes'),

                    RkNavigation::make('grupo-gastos')
                        ->setParentId('config-presupuestaria')
                        ->setDescription('Gestión de grupos de gastos')
                        ->setLabel('Grupos de gastos')
                        ->setHeroIcon('receipt-percent')
                        ->setItems([])
                        ->setEndBlock('grupo-gastos'),

                    RkNavigation::make('estados-ejecucion')
                        ->setParentId('config-presupuestaria')
                        ->setDescription('Gestión de estados de ejecución presupuestaria')
                        ->setLabel('Estados de ejecución')
                        ->setHeroIcon('clipboard-document-check')
                        ->setItems([])
                        ->setEndBlock('estados-ejecucion'),
                ])
                ->setEndBlock('config-presupuestaria'),

            RkNavigation::makeGroup('config-procesos')
                ->setParentId('configuracion')
                ->setLabel('Procesos')
                ->setHeroIcon('cog-8-tooth')
                ->setItems([

                    RkNavigation::make('procesoscompras')
                        ->setParentId('config-procesos')
                        ->setDescription('Gestiona los procesos de compras')
                        ->setLabel('Compras')
                        ->setHeroIcon('shopping-bag')
                        ->setItems([])
                        ->setEndBlock('procesoscompras'),

                    RkNavigation::make('estados-requisicion')
                        ->setParentId('config-procesos')
                        ->setDescription('Gestión de estados de requisición')
                        ->setLabel('Estados requisición')
                        ->setHeroIcon('check-circle')
                        ->setItems([])
                        ->setEndBlock('estados-requisicion'),

                    RkNavigation::make('tipo-acta-entregas')
                        ->setParentId('config-procesos')
                        ->setDescription('Gestión de tipos de acta de entregas')
                        ->setLabel('Tipos acta')
                        ->setHeroIcon('document-check')
                        ->setItems([])
                        ->setEndBlock('tipo-acta-entregas'),
                ])
                ->setEndBlock('config-procesos'),

            RkNavigation::makeGroup('catalogos-generales')
                ->setParentId('configuracion')
                ->setLabel('Catálogos')
                ->setHeroIcon('squares-2x2')
                ->setItems([

                    RkNavigation::make('categorias')
                        ->setParentId('catalogos-generales')
                        ->setDescription('Gestión de categorías')
                        ->setLabel('Categorías')
                        ->setHeroIcon('squares-plus')
                        ->setItems([])
                        ->setEndBlock('categorias'),

                    RkNavigation::make('tipoactividades')
                        ->setParentId('catalogos-generales')
                        ->setDescription('Gestión de tipos de actividades')
                        ->setLabel('Tipo actividades')
                        ->setHeroIcon('tag')
                        ->setItems([])
                        ->setEndBlock('tipoactividades'),

                    RkNavigation::make('unidad-medidas')
                        ->setParentId('catalogos-generales')
                        ->setDescription('Gestión de unidades de medida')
                        ->setLabel('Unidades medida')
                        ->setHeroIcon('scale')
                        ->setItems([])
                        ->setEndBlock('unidad-medidas'),

                    RkNavigation::make('trimestres')
                        ->setParentId('catalogos-generales')
                        ->setDescription('Gestión de trimestres')
                        ->setLabel('Trimestres')
                        ->setHeroIcon('calendar-days')
                        ->setItems([])
                        ->setEndBlock('trimestres'),
                ])
                ->setEndBlock('catalogos-generales'),
        ])
        ->setEndBlock('configuracion'),

    RkNavigation::makeGroup('consola')
        ->setLabel('Consola')
        ->setHeroIcon('terminal')
        ->setItems([

            RkNavigation::make('planestrategicoinstitucional')
                ->setParentId('consola')
                ->setDescription('Visualiza y gestiona el plan estratégico')
                ->setLabel('Plan estratégico')
                ->setHeroIcon('document-text')
                ->setItems([])
                ->setEndBlock('planestrategicoinstitucional'),

            RkNavigation::make('asignacionnacionalpresupuestaria')
                ->setParentId('consola')
                ->setDescription('Gestión de la asignación presupuestaria nacional')
                ->setLabel('Asignación nacional')
                ->setHeroIcon('chart-pie')
                ->setItems([
                    RkNavigation::make('techonacional')
                        ->setDescription('Gestión de techos presupuestarios por Unidades Ejecutoras')
                        ->setLabel('Techos presupuestarios UE')
                        ->setHeroIcon('arrow-trending-up')
                        ->setItems([
                            RkNavigation::make('plazos')
                                ->setLabel('Plazos de asignación')
                                ->setDescription('Define y gestiona los plazos para la asignación presupuestaria nacional, departamental y planificación')
                        ])
                        ->setEndBlock('techonacional'),
                ])
                ->setEndBlock('asignacionnacionalpresupuestaria'),

            RkNavigation::make('asignacionpresupuestaria')
                ->setParentId('consola')
                ->setDescription('Gestión de la asignación presupuestaria')
                ->setLabel('Asignación presupuestaria')
                ->setHeroIcon('banknotes')
                ->setItems([
                    RkNavigation::make('techodeptos')
                        ->setDescription('Gestión de techos presupuestarios por departamento')
                        ->setLabel('Techos presupuestarios')
                        ->setHeroIcon('building-storefront')
                        ->setItems([
                            RkNavigation::make('techodeptos.detalle-estructura')
                                ->setLabel('Detalle de estructura')
                                ->setDescription('Detalle de asignaciones a departamentos por estructura')
                        ])
                        ->setEndBlock('techodeptos'),
                ])
                ->setEndBlock('asignacionpresupuestaria'),
            /*
            RkNavigation::make('techodeptos')
                ->setParentId('consola')
                ->setDescription('Gestión de techos presupuestarios por departamento')
                ->setLabel('Techos presupuestarios')
                ->setHeroIcon('building-storefront')
                ->setItems([])
                ->setEndBlock('techodeptos'),

            RkNavigation::make('techonacional')
                ->setParentId('consola')
                ->setDescription('Gestión de techos presupuestarios por Unidades Ejecutoras')
                ->setLabel('Techos presupuestarios UE')
                ->setHeroIcon('arrow-trending-up')
                ->setItems([])
                ->setEndBlock('techonacional'), */
        ])
        ->setEndBlock('consola'),

    RkNavigation::makeGroup('sistema-monitoreo')
        ->setLabel('Sistema')
        ->setHeroIcon('computer-desktop')
        ->setItems([

            RkNavigation::makeGroup('logs-auditoria')
                ->setParentId('sistema-monitoreo')
                ->setLabel('Logs')
                ->setHeroIcon('document-text')
                ->setItems([

                    RkNavigation::make('logs')
                        ->setParentId('logs-auditoria')
                        ->setDescription('Visualiza los logs del sistema')
                        ->setLabel('Visor de Logs')
                        ->setHeroIcon('eye')
                        ->setItems([])
                        ->setEndBlock('logs'),

                    RkNavigation::make('logsdashboard')
                        ->setParentId('logs-auditoria')
                        ->setDescription('Resumen y métricas de logs')
                        ->setLabel('Dashboard de Logs')
                        ->setHeroIcon('chart-pie')
                        ->setItems([])
                        ->setEndBlock('logsdashboard'),
                ])
                ->setEndBlock('logs-auditoria'),

            RkNavigation::makeGroup('sesiones-seguridad')
                ->setParentId('sistema-monitoreo')
                ->setLabel('Seguridad')
                ->setHeroIcon('shield-check')
                ->setItems([

                    RkNavigation::make('sessions')
                        ->setParentId('sesiones-seguridad')
                        ->setDescription('Monitoreo de sesiones activas')
                        ->setLabel('Sesiones')
                        ->setHeroIcon('users')
                        ->setItems([])
                        ->setEndBlock('sessions'),
                ])
                ->setEndBlock('sesiones-seguridad'),
        ])
        ->setEndBlock('sistema-monitoreo'),
];
