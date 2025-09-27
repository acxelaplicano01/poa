<?php

use Rk\RoutingKit\Entities\RkNavigation;

return [

    // Dashboard / Inicio
    RkNavigation::makeGroup('dashboard_group')
        ->setLabel('Inicio')
        ->setDescription('Panel principal del sistema')
        ->setHeroIcon('home')
        ->setItems([
            RkNavigation::makeSimple('dashboard')
                ->setLabel('Panel Principal')
                ->setDescription('Accede al panel principal')
                ->setHeroIcon('home')
                ->setEndBlock('dashboard')
        ])
        ->setEndBlock('dashboard_group'),

    // Planificación
    RkNavigation::makeGroup('planificacion')
        ->setLabel('Planificación')
        ->setHeroIcon('calendar')
        ->setItems([
            RkNavigation::make('planificar')
                ->setLabel('Mis planificaciones')
                ->setDescription('Visualiza tus planificaciones')
                ->setHeroIcon('document-text')
                ->setEndBlock('planificar'),

            RkNavigation::make('requerir')
                ->setLabel('Requerir')
                ->setDescription('Crear o gestionar requerimientos')
                ->setHeroIcon('clipboard-document')
                ->setEndBlock('requerir'),

            RkNavigation::make('seguimiento')
                ->setLabel('Dar seguimiento')
                ->setDescription('Seguimiento de planificaciones')
                ->setHeroIcon('eye')
                ->setEndBlock('seguimiento'),

            RkNavigation::make('consolidado')
                ->setLabel('Consolidado')
                ->setDescription('Genera reportes consolidados')
                ->setHeroIcon('chart-bar-square')
                ->setEndBlock('consolidado'),
        ])
        ->setEndBlock('planificacion'),

    // Configuración
    RkNavigation::makeGroup('configuracion')
        ->setLabel('Configuración')
        ->setHeroIcon('cog-6-tooth')
        ->setItems([
            
            // Subgrupo: Gestión de Usuarios y Accesos
            RkNavigation::makeGroup('usuarios-accesos')
                ->setLabel('Usuarios')
                ->setHeroIcon('user-group')
                ->setItems([
                    RkNavigation::make('roles')
                        ->setLabel('Roles')
                        ->setDescription('Gestiona roles de usuario')
                        ->setHeroIcon('shield-exclamation')
                        ->setEndBlock('roles'),

                    RkNavigation::make('usuarios')
                        ->setLabel('Usuarios')
                        ->setDescription('Administración de usuarios')
                        ->setHeroIcon('users')
                        ->setEndBlock('usuarios'),

                    RkNavigation::make('empleados')
                        ->setLabel('Empleados')
                        ->setDescription('Gestión de empleados')
                        ->setHeroIcon('identification')
                        ->setEndBlock('empleados'),
                ])
                ->setEndBlock('usuarios-accesos'),

            // Subgrupo: Estructura Organizacional
            RkNavigation::makeGroup('estructura-organizacional')
                ->setLabel('Organización')
                ->setHeroIcon('building-office')
                ->setItems([
                    RkNavigation::make('departamentos')
                        ->setLabel('Departamentos')
                        ->setDescription('Administración de departamentos')
                        ->setHeroIcon('building-office')
                        ->setEndBlock('departamentos'),

                    RkNavigation::make('instituciones')
                        ->setLabel('Instituciones')
                        ->setDescription('Gestión de instituciones')
                        ->setHeroIcon('building-office-2')
                        ->setEndBlock('instituciones'),

                    RkNavigation::make('cubs')
                        ->setLabel('Cubs')
                        ->setDescription('Administración de cubs')
                        ->setHeroIcon('cube')
                        ->setEndBlock('cubs'),
                ])
                ->setEndBlock('estructura-organizacional'),

            // Subgrupo: Configuración Presupuestaria
            RkNavigation::makeGroup('config-presupuestaria')
                ->setLabel('Presupuesto')
                ->setHeroIcon('banknotes')
                ->setItems([
                    RkNavigation::make('fuentes')
                        ->setLabel('Fuentes')
                        ->setDescription('Gestión de fuentes de financiamiento')
                        ->setHeroIcon('currency-dollar')
                        ->setEndBlock('fuentes'),

                    RkNavigation::make('grupo-gastos')
                        ->setLabel('Grupos de gastos')
                        ->setDescription('Gestión de grupos de gastos')
                        ->setHeroIcon('receipt-percent')
                        ->setEndBlock('grupo-gastos'),

                    RkNavigation::make('estados-ejecucion')
                        ->setLabel('Estados de ejecución')
                        ->setDescription('Gestión de estados de ejecución presupuestaria')
                        ->setHeroIcon('clipboard-document-check')
                        ->setEndBlock('estados-ejecucion'),
                ])
                ->setEndBlock('config-presupuestaria'),

            // Subgrupo: Configuración de Procesos
            RkNavigation::makeGroup('config-procesos')
                ->setLabel('Procesos')
                ->setHeroIcon('cog-8-tooth')
                ->setItems([
                    RkNavigation::make('procesoscompras')
                        ->setLabel('Compras')
                        ->setDescription('Gestiona los procesos de compras')
                        ->setHeroIcon('shopping-bag')
                        ->setEndBlock('procesoscompras'),

                    RkNavigation::make('estados-requisicion')
                        ->setLabel('Estados requisición')
                        ->setDescription('Gestión de estados de requisición')
                        ->setHeroIcon('check-circle')
                        ->setEndBlock('estados-requisicion'),

                    RkNavigation::make('tipo-acta-entregas')
                        ->setLabel('Tipos acta')
                        ->setDescription('Gestión de tipos de acta de entregas')
                        ->setHeroIcon('document-check')
                        ->setEndBlock('tipo-acta-entregas'),
                ])
                ->setEndBlock('config-procesos'),

            // Subgrupo: Catálogos Generales
            RkNavigation::makeGroup('catalogos-generales')
                ->setLabel('Catálogos')
                ->setHeroIcon('squares-2x2')
                ->setItems([

                    RkNavigation::make('categorias')
                        ->setLabel('Categorías')
                        ->setDescription('Gestión de categorías')
                        ->setHeroIcon('squares-plus')
                        ->setEndBlock('categorias'),

                    RkNavigation::make('tipoactividades')
                        ->setLabel('Tipo actividades')
                        ->setDescription('Gestión de tipos de actividades')
                        ->setHeroIcon('tag')
                        ->setEndBlock('tipoactividades'),

                    RkNavigation::make('unidad-medidas')
                        ->setLabel('Unidades medida')
                        ->setDescription('Gestión de unidades de medida')
                        ->setHeroIcon('scale')
                        ->setEndBlock('unidad-medidas'),

                    RkNavigation::make('trimestres')
                        ->setLabel('Trimestres')
                        ->setDescription('Gestión de trimestres')
                        ->setHeroIcon('calendar-days')
                        ->setEndBlock('trimestres'),
                ])
                ->setEndBlock('catalogos-generales'),
        ])
        ->setEndBlock('configuracion'),

    // Consola de Administración
    RkNavigation::makeGroup('consola')
        ->setLabel('Consola')
        ->setHeroIcon('terminal')
        ->setItems([
            RkNavigation::make('planestrategicoinstitucional')
                ->setLabel('Plan estratégico')
                ->setDescription('Visualiza y gestiona el plan estratégico')
                ->setHeroIcon('document-text')
                ->setEndBlock('planestrategicoinstitucional'),

            RkNavigation::make('asignacionpresupuestaria')
                ->setLabel('Asignación presupuestaria')
                ->setDescription('Gestión de la asignación presupuestaria')
                ->setHeroIcon('banknotes')
                ->setEndBlock('asignacionpresupuestaria'),

            RkNavigation::make('techodeptos')
                ->setLabel('Techos presupuestarios')
                ->setDescription('Gestión de techos presupuestarios por departamento')
                ->setHeroIcon('building-storefront')
                ->setEndBlock('techodeptos'),
        ])
        ->setEndBlock('consola'),

    // Sistema y Monitoreo
    RkNavigation::makeGroup('sistema-monitoreo')
        ->setLabel('Sistema')
        ->setHeroIcon('computer-desktop')
        ->setItems([
            
            // Subgrupo: Logs y Auditoría
            RkNavigation::makeGroup('logs-auditoria')
                ->setLabel('Logs')
                ->setHeroIcon('document-text')
                ->setItems([
                    RkNavigation::make('logs')
                        ->setLabel('Visor de Logs')
                        ->setDescription('Visualiza los logs del sistema')
                        ->setHeroIcon('eye')
                        ->setEndBlock('logs'),

                    RkNavigation::make('logsdashboard')
                        ->setLabel('Dashboard de Logs')
                        ->setDescription('Resumen y métricas de logs')
                        ->setHeroIcon('chart-pie')
                        ->setEndBlock('logsdashboard'),
                ])
                ->setEndBlock('logs-auditoria'),

            // Subgrupo: Sesiones y Seguridad
            RkNavigation::makeGroup('sesiones-seguridad')
                ->setLabel('Seguridad')
                ->setHeroIcon('shield-check')
                ->setItems([

                    RkNavigation::make('sessions')
                        ->setLabel('Sesiones')
                        ->setDescription('Monitoreo de sesiones activas')
                        ->setHeroIcon('users')
                        ->setEndBlock('sessions'),
                ])
                ->setEndBlock('sesiones-seguridad'),
        ])
        ->setEndBlock('sistema-monitoreo'),

];
