<?php
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
                'always_visible' => true
            ]
        ]
    ],

    // Módulo de planificación
    'planificacion' => [
        'titulo' => 'Planificación',
        'icono' => '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m4 10v-2m3 2v-6m3 6v-3m4-11v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />',
        'route' => 'planificar',
        'items' => [
            [
                'titulo' => 'Mis planificaciones',
                'route' => 'planificar',
                'routes' => ['planificar'],
                'permisos' => ['planificacion.planificar'],
                'icono' => ''
            ],
            [
                'titulo' => 'Requerir',
                'route' => 'requerir',
                'routes' => ['planificar.requerir'],
                'permisos' => ['planificacion.requerir'],
                'icono' => ''
            ],
            [
                'titulo' => 'Dar seguimiento',
                'route' => 'seguimiento',
                'routes' => ['planificar.seguimiento'],
                'permisos' => ['planificacion.seguimiento'],
                'icono' => ''
            ],
            [
                'titulo' => 'Consolidado',
                'route' => 'consolidado',
                'routes' => ['planificar.consolidado'],
                'permisos' => ['planificacion.consolidado'],
                'icono' => ''
            ]
        ]
    ],

    // Módulo de configuración
    'configuracion' => [
        'titulo' => 'Configuración',
        'icono' => '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" /><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />',
        'route' => 'roles',
        'items' => [
            [
                'titulo' => 'Roles',
                'route' => 'roles',
                'routes' => ['roles'],
                'permisos' => ['configuracion.roles'],
                'icono' => '',
                'default_route' => true // Esta sería la ruta por defecto si el usuario tiene permiso
            ],
            [
                'titulo' => 'Usuarios',
                'route' => 'usuarios',
                'routes' => ['usuarios'],
                'permisos' => ['configuracion.usuarios'],
                'icono' => ''
            ],
            [
                'titulo' => 'Empleados',
                'route' => 'empleados',
                'routes' => ['empleados'],
                'permisos' => ['configuracion.empleados'],
                'icono' => ''
            ],
            [
                'titulo' => 'Departamentos',
                'route' => 'departamentos',
                'routes' => ['departamentos'],
                'permisos' => ['configuracion.departamentos'],
                'icono' => ''
            ],
            [
                'titulo' => 'Procesos de compras',
                'route' => 'procesoscompras',
                'routes' => ['procesoscompras'],
                'permisos' => ['configuracion.procesoscompras'],
                'icono' => ''
            ],
            [
                'titulo' => 'Cubs',
                'route' => 'cubs',
                'routes' => ['cubs'],
                'permisos' => ['configuracion.cubs'],
                'icono' => ''
            ],
        ],
        //para que el modulo se muestre en el footer
        'footer' => true
    ],

    // Módulo de gestion administrativa
   /* 'gestion' => [
        'titulo' => 'Gestión Administrativa',
        'icono' => '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z" />',
        'route' => 'gestionadministrativa',
        'items' => [
            [
                'titulo' => 'Gestion Administrativa',
                'route' => 'gestionadministrativa',
                'routes' => ['gestionadministrativa'],
                'permisos' => ['gestionadministrativa.gestionadministrativa'],
                'icono' => ''
            ],
            [
                'titulo' => 'Configuración',
                'route' => 'configuracion',
                'routes' => ['configuracion'],
                'permisos' => ['gestionadministrativa.configuracion'],
                'icono' => ''
            ],
            [
                'titulo' => 'Plan Anual de Compras',
                'route' => 'plananualcompras',
                'routes' => ['plananualcompras'],
                'permisos' => ['gestionadministrativa.plananualcompras'],
                'icono' => ''
            ],
        ]
    ],

    // Módulo de reportes
    'reportes' => [
        'titulo' => 'Reportes',
        'icono' => '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z" />',
        'route' => 'reportegeneral',
        'items' => [
            [
                'titulo' => 'Reporte general',
                'route' => 'reportegeneral',
                'routes' => ['reportegeneral'],
                'permisos' => ['reportes.reportegeneral'],
                'icono' => ''
            ],
            [
                'titulo' => 'Resumen trimestral',
                'route' => 'resumentrimestral',
                'routes' => ['resumentrimestral'],
                'permisos' => ['reportes.resumentrimestral'],
                'icono' => ''
            ],
            [
                'titulo' => 'Consolidado',
                'route' => 'consolidado',
                'routes' => ['consolidado'],
                'permisos' => ['reportes.consolidado'],
                'icono' => ''
            ],
            [
                'titulo' => 'Recursos planificados',
                'route' => 'recursosplanificados',
                'routes' => ['recursosplanificados'],
                'permisos' => ['reportes.recursosplanificados'],
                'icono' => ''
            ],
        ]
    ],

    // Modulo Consola
    'consola' => [
        'titulo' => 'Consola',
        'icono' => '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z" />',
        'route' => 'consola',
        'items' => [
            [
                'titulo' => 'Plan estratégico institucional',
                'route' => 'planestrategico',
                'routes' => ['planestrategico'],
                'permisos' => ['consola.planestrategico'],
                'icono' => ''
            ],
            [
                'titulo' => 'Asignación presupuestaria',
                'route' => 'asignacionpresupuestaria',
                'routes' => ['asignacionpresupuestaria'],
                'permisos' => ['consola.asignacionpresupuestaria'],
                'icono' => ''
            ],
        ]
    ]*/
];