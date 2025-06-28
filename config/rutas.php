<?php
// filepath: c:\Users\acxel\Desktop\Desarrollo\Git Repos\POA\config\rutas.php

/*
 * Estructura de Permisos Jerárquica (3 niveles):
 * 
 * 1. Módulo Padre: acceso-{modulo} (ej: acceso-configuracion)
 *    - Permiso principal que controla el acceso general al módulo
 * 
 * 2. Funcionalidad: {modulo}.{funcionalidad} (ej: configuracion.roles)
 *    - Agrupa las acciones relacionadas con una funcionalidad específica
 * 
 * 3. Acciones: {modulo}.{funcionalidad}.{accion} (ej: configuracion.roles.crear)
 *    - Permisos específicos para cada acción (crear, editar, eliminar, ver, etc.)
 * 
 * Propiedades de configuración:
 * - permisos_modulo: Define el permiso padre del módulo completo
 * - permisos: Array de permisos específicos requeridos para acceder a la ruta
 */

return [
    // Módulo de Dashboard/Inicio
    'dashboard' => [
        'titulo' => 'Inicio',
        'icono' => '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />',
        'route' => 'dashboard',
        'items' => [
            [
                'titulo' => 'Panel Principal',
                'route' => 'dashboard',
                'routes' => ['dashboard'],
                'permisos' => [],
                'icono' => '',
                'always_visible' => true,
                'breadcrumb' => true // Indica que este elemento debe aparecer en el breadcrumb
            ]
        ]
    ],

    // Módulo de planificación
    'planificacion' => [
        'titulo' => 'Planificación',
        'icono' => '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m4 10v-2m3 2v-6m3 6v-3m4-11v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />',
        'route' => 'planificar',
        'breadcrumb_label' => 'Planificación',
        'permisos_modulo' => 'acceso-planificacion', // Permiso padre del módulo
        'items' => [
            [
                'titulo' => 'Mis planificaciones',
                'route' => 'planificar',
                'routes' => ['planificar'],
                'permisos' => ['planificacion.planificar.ver'], // Solo permiso de acceso a la página
                'icono' => '',
                'breadcrumb' => true
            ],
            [
                'titulo' => 'Requerir',
                'route' => 'requerir',
                'routes' => ['requerir'],
                'permisos' => ['planificacion.requerir.ver'], // Solo permiso de acceso a la página
                'icono' => '',
                'breadcrumb' => true,
            ],
            [
                'titulo' => 'Dar seguimiento',
                'route' => 'seguimiento',
                'routes' => ['seguimiento'],
                'permisos' => ['planificacion.seguimiento.ver'], // Solo permiso de acceso a la página
                'icono' => '',
                'breadcrumb' => true,
            ],
            [
                'titulo' => 'Consolidado',
                'route' => 'consolidado',
                'routes' => ['consolidado'],
                'permisos' => ['planificacion.consolidado.ver', 'planificacion.consolidado.generar'],
                'icono' => '',
                'breadcrumb' => true,
            ]
        ]
    ],

    // Módulo de configuración
    'configuracion' => [
        'titulo' => 'Configuración',
        'icono' => '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" /><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />',
        'route' => 'roles',
        'breadcrumb_label' => 'Configuración',
        'permisos_modulo' => 'acceso-configuracion', // Permiso padre del módulo
        'items' => [
            [
                'titulo' => 'Roles',
                'route' => 'roles',
                'routes' => ['roles'],
                'permisos' => ['configuracion.roles.ver'], // Solo permiso de acceso a la página
                'icono' => '',
                'default_route' => true,
                'breadcrumb' => true
            ],
            [
                'titulo' => 'Usuarios',
                'route' => 'usuarios',
                'routes' => ['usuarios'],
                'permisos' => ['configuracion.usuarios.ver'], // Solo permiso de acceso a la página
                'icono' => '',
                'breadcrumb' => true
            ],
            [
                'titulo' => 'Empleados',
                'route' => 'empleados',
                'routes' => ['empleados'],
                'permisos' => ['configuracion.empleados.ver'], // Solo permiso de acceso a la página
                'icono' => '',
                'breadcrumb' => true
            ],
            [
                'titulo' => 'Departamentos',
                'route' => 'departamentos',
                'routes' => ['departamentos'],
                'permisos' => ['configuracion.departamentos.ver'], // Solo permiso de acceso a la página
                'icono' => '',
                'breadcrumb' => true
            ],
            [
                'titulo' => 'Procesos de compras',
                'route' => 'procesoscompras',
                'routes' => ['procesoscompras'],
                'permisos' => ['configuracion.procesoscompras.ver'], // Solo permiso de acceso a la página
                'icono' => '',
                'breadcrumb' => true
            ],
            [
                'titulo' => 'Cubs',
                'route' => 'cubs',
                'routes' => ['cubs'],
                'permisos' => ['configuracion.cubs.ver'], // Solo permiso de acceso a la página
                'icono' => '',
                'breadcrumb' => true
            ],
        ],
        'footer' => true
    ],

    // Módulo de Consola de Administración
    'consola' => [
        'titulo' => 'Consola',
        'icono' => '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5"/>',
        'route' => 'planestrategicoinstitucional', // Ruta principal del módulo
        'permisos_modulo' => 'acceso-consola', // Permiso padre del módulo
        'breadcrumb_label' => 'Consola',
        'items' => [
            [
                'titulo' => 'Plan estratégico institucional',
                'route' => 'planestrategicoinstitucional',
                'routes' => ['planestrategicoinstitucional'],
                'permisos' => ['consola.planestrategicoinstitucional.ver'],
                'icono' => '',
                'breadcrumb' => true
            ],
            [
                'titulo' => 'Asignación presupuestaria',
                'route' => 'asignacionpresupuestaria',
                'routes' => ['asignacionpresupuestaria'],
                'permisos' => ['consola.asignacionpresupuestaria.ver'],
                'icono' => '',
                'breadcrumb' => true
            ],
        ]
    ],

    'logs' => [
        'titulo' => 'Registros del Sistema',
        'icono' => '<path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 6H10m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4m16 6h-2m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4m16 6H10m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4"/>',
        'route' => 'logs',
        'breadcrumb_label' => 'Registros del Sistema',
        'permisos_modulo' => 'acceso-logs', // Permiso padre del módulo
        'items' => [
            [
                'titulo' => 'Visor de Logs',
                'route' => 'logs',
                'routes' => ['logs'],
                'permisos' => ['logs.visor.ver'],
                'icono' => '',
                'breadcrumb' => true
            ],
            [
                'titulo' => 'Dashboard de Logs',
                'route' => 'logsdashboard',
                'routes' => ['logsdashboard'],
                'permisos' => ['logs.dashboard.ver'],
                'icono' => '',
                'breadcrumb' => true,
            ],
            [
                'titulo' => 'Sesiones',
                'route' => 'sessions',
                'routes' => ['sessions'],
                'permisos' => ['logs.sessions.ver'],
                'icono' => '',
                'breadcrumb' => true,
                //'parent_breadcrumb' => 'logs'
            ]
        ],
        'footer' => true
    ]
];