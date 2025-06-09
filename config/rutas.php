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
                'routes' => [
                    'planificar',
                    'planificar.nuevo',
                    'planificar.editar',
                    'planificar.ver'
                ],
                'permisos' => ['planificacion.planificar'],
                'icono' => ''
            ],
           /* [
                'titulo' => 'Proyectos',
                'route' => 'planificar.proyectos',
                'routes' => [
                    'planificar.proyectos',
                    'planificar.proyectos.nuevo',
                    'planificar.proyectos.editar'
                ],
                'permisos' => ['planificacion.proyectos'],
                'icono' => ''
            ],
            [
                'titulo' => 'Seguimiento',
                'route' => 'planificar.seguimiento',
                'routes' => ['planificar.seguimiento'],
                'permisos' => ['planificacion.seguimiento'],
                'icono' => ''
            ]*/
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
                'routes' => ['roles', 'roles.crear', 'roles.editar', 'roles.permisos'],
                'permisos' => ['configuracion.roles'],
                'icono' => ''
            ],
            [
                'titulo' => 'Usuarios',
                'route' => 'usuarios',
                'routes' => ['usuarios', 'usuarios.crear', 'usuarios.editar'],
                'permisos' => ['configuracion.usuarios'],
                'icono' => ''
            ],
        ],
        'footer' => true
    ]
];