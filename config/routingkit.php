<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Support
    |--------------------------------------------------------------------------
    |
    | This option enables or disables specific functionalities related to
    | Livewire components within your package. When set to true, it might
    | activate Livewire-specific parsing or routing behaviors.
    |
    */
    'livewire_support' => true,


    /*    
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | Specify the fully qualified class name (FQCN) of your User model.
    | This is used by the package to reference user-related data and
    | functionalities, such as authentication and role management.
    |  
    */

    'user_model' => \App\Models\User::class,



    /*
    |--------------------------------------------------------------------------
    | Controllers Paths
    |--------------------------------------------------------------------------
    |
    | Define the base paths where your application's controllers and Livewire
    | components are located. These paths are used, for example, by the
    | RkFileBrowser to allow interactive selection of classes.
    |
    | Each key represents a directory path (relative to base_path()),
    | and its value is a user-friendly label for that path.
    |
    */

    'controllers_path' => [
        base_path('app/Http/Controllers'),
        base_path('app/Livewire')
    ],


    /*
    |--------------------------------------------------------------------------
    | Stubs Configuration
    |--------------------------------------------------------------------------
    |
    | Define templates (stubs) for generating controllers and views.    
    | Each stub configuration includes details such as the default name,
    | file extension, path to the stub file, and whether it should be
    | included in routing and navigation generation.
    |
    | You can also specify associated views for each controller stub.
    | This allows for a structured approach to generating both controllers
    | and their corresponding views based on predefined templates.
    |
    */

    'stubs' => [

        'identificador' => [

            'controllers' => [

                [

                    'default_name' => '{modelo}Controller',
                    'extension' => '.php',
                    'stub_path' => base_path('routingkit/Stubs/simple/simplecontroller.blade.php'),
                    'stub_type' => 'blade',
                    'rk_route' => true,
                    'rk_navigation' => true,
                    'views' => [

                        [
                            'extension' => '.blade.php',
                            'stub_path' => base_path('routingkit/Stubs/simple/simpleviewcontroller.blade.php'),
                            'stub_type' => 'blade'
                        ],

                    ]
                ]
            ],


        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Route Entity Model
    |--------------------------------------------------------------------------
    |
    | Specify the fully qualified class name (FQCN) of the model that
    | represents your routes. This model should extend RkBaseEntity
    | and is used throughout the RoutingKit package for managing route data.
    |
    */
    
    'model_ussage' => \Rk\RoutingKit\Entities\RkRoute::class,

    /*
    |--------------------------------------------------------------------------
    | Route File Paths Configuration
    |--------------------------------------------------------------------------
    |
    | Configure where your routes will be saved and how they are structured.
    | This section defines different storage strategies for your routes.
    |
    | 'default_file': Specifies the default strategy (key from 'items').
    | 'items': An array defining each route storage strategy.
    |   - 'path': The full path to the file where routes are stored.
    |   - 'support_file': Indicates the structure of the data within the file
    |                     (e.g., "object_file_tree", "object_file_plain").
    |   - 'only_string_support': If true, indicates that the file expects
    |                            only string values for certain configurations.
    |
    */

    'routes_file_path' => [

        'default_file' => 'dashboard_routes',
        'items'        => [

            'dashboard_routes' => [
                'path'              => base_path('routingkit/Routes/rkRoutes.php'),
                'support_file'      => "object_file_tree",
                'only_string_support' => false,
            ],

            //...
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Navigation File Paths Configuration
    |--------------------------------------------------------------------------
    |
    | Configure where your navigation data will be saved and how it is structured.
    | This section defines different storage strategies for your navigations,
    | similar to how routes are handled.
    |
    | 'default_file': Specifies the default navigation file (key from 'items').
    | 'items': An array defining each navigation storage strategy.
    |   - 'path': The full path to the file where navigation data is stored.
    |   - 'support_file': Indicates the structure of the data within the file.
    |   - 'only_string_support': If true, indicates that the file expects
    |                            only string values for certain configurations.
    |
    */

    'navigators_file_path' => [

        'default_file' => 'dashboard_navigators',
        'items'        => [

            'dashboard_navigators' => [
                'path'              => base_path('routingkit/Navigation/rkNavigation.php'),
                'support_file'      => "object_file_tree",
                'only_string_support' => true,
            ],

             'rk_footer' => [
                'path'              => base_path('routingkit/Navigation/rkFooter.php'),
                'support_file'      => "object_file_tree",
                'only_string_support' => true,
            ],

            // ...
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Roles
    |--------------------------------------------------------------------------
    |
    | Define the roles available in your application. These roles can be
    | used for permission management and access control within your routes
    | and navigation. These are typically used for console prompts
    | and internal logic.
    |
    | The keys are the internal identifiers for the roles, and the values
    | are often the same for simplicity or can be descriptive names.
    |
    */
    
    'roles' => [

        [
            'id'   => 'super_admin',
            'name' => 'Super Admin',
            'for_tenant' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Development Users
    |--------------------------------------------------------------------------
    |
    | Configure credentials and roles for development-specific users.
    | These users can be automatically created or seeded during development
    | to facilitate testing and access control setup.
    |
    | It's recommended to store sensitive information like email and password
    | in your .env file and access them using the env() helper.
    |
    */

    'development_users' => [
        'admin_general' => [
            'user' => [
                'name' => "Acxel Aplicano",
                'email' => env('MAIL_ADMIN_ADDRESS') ?? 'admin@gmail.com',
                'password' => env('MAIL_ADMIN_PASSWORD') ?? '12345678',
            ],
            'roles'    => ['super_admin'],
        ],
    ],

];
