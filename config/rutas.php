<?php
return [
    // Módulo de configuración
    'configuracion' => [
        [
            'name' => 'Roles',
            'route' => 'roles',
            'translation_key' => 'Roles',
            'permissions' => ['configuracion.roles']
        ],
        [
            'name' => 'Usuarios',
            'route' => 'usuarios',
            'translation_key' => 'Usuarios', 
            'permissions' => ['configuracion.usuarios']
        ],
       /* [
            'name' => 'Empleados',
            'route' => 'empleados', 
            'translation_key' => 'Empleados',
            'permissions' => ['configuracion.empleados']
        ],
        [
            'name' => 'Departamentos',
            'route' => 'departamentos',
            'translation_key' => 'Departamentos',
            'permissions' => ['configuracion.departamentos']
        ],
        [
            'name' => 'Procesos de compras',
            'route' => 'procesos-compras',
            'translation_key' => 'Procesos de compras',
            'permissions' => ['configuracion.procesos-compras']
        ],
        [
            'name' => 'Cubs',
            'route' => 'cubs',
            'translation_key' => 'Cubs',
            'permissions' => ['configuracion.cubs']
        ],*/
    ],
    
    // Módulo de planificación
    'planificacion' => [
        [
            'name' => 'Planificar',
            'route' => 'planificar',
            'translation_key' => 'Planificar',
            'permissions' => ['planificacion.planificar']
        ],
        /*[
            'name' => 'Seguimiento',
            'route' => 'seguimiento',
            'translation_key' => 'Seguimiento',
            'permissions' => ['planificacion.seguimiento']
        ],*/
    ],
    
    // Módulo de gestión
    /*'gestion' => [
        [
            'name' => 'Gestión 1',
            'route' => 'gestion1',
            'translation_key' => 'Gestión 1',
            'permissions' => ['gestion.gestion1']
        ],
        [
            'name' => 'Gestión 2',
            'route' => 'gestion2',
            'translation_key' => 'Gestión 2',
            'permissions' => ['gestion.gestion2']
        ],
    ],*/
    
   /* // Módulo de reportes
    'reportes' => [
        [
            'name' => 'Reporte 1',
            'route' => 'reporte1',
            'translation_key' => 'Reporte 1',
            'permissions' => ['reportes.reporte1']
        ],
        [
            'name' => 'Reporte 2',
            'route' => 'reporte2',
            'translation_key' => 'Reporte 2',
            'permissions' => ['reportes.reporte2']
        ],
    ],*/
    
   /* // Módulo de consolas
    'consolas' => [
        [
            'name' => 'Consola 1',
            'route' => 'consola1',
            'translation_key' => 'Consola 1',
            'permissions' => ['consolas.consola1']
        ],
        [
            'name' => 'Consola 2',
            'route' => 'consola2',
            'translation_key' => 'Consola 2',
            'permissions' => ['consolas.consola2']
        ],
    ],*/
];