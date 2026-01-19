<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use App\Models\Requisicion\Requisicion;
use App\Observers\RequisicionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Permite que usuarios con super-admin omitan todas las verificaciones
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Gate para verificar acceso a módulos
        Gate::define('acceder-modulo', function ($user, $module) {
            // Si es super-admin, siempre tiene acceso
            if ($user->hasRole('super-admin')) {
                return true;
            }

            // Verificar el permiso específico del módulo
            return $user->can("acceso-{$module}");
        });

        // Gate para permisos específicos dentro de un módulo
        Gate::define('usar-funcionalidad', function ($user, $permission) {
            // Si es super-admin, siempre tiene permiso
            if ($user->hasRole('super-admin')) {
                return true;
            }

            // Si el permiso incluye un punto, verificar que tenga acceso al módulo padre
            if (strpos($permission, '.') !== false) {
                list($module, $action) = explode('.', $permission, 2);
                $moduleAccess = "acceso-{$module}";

                // Si no tiene acceso al módulo, no puede usar ninguna funcionalidad
                if (!$user->can($moduleAccess)) {
                    return false;
                }
            }

            // Verificar el permiso específico
            return $user->can($permission);
        });
        
        // Registrar Observers
        \Spatie\Permission\Models\Role::observe(\App\Observers\RolePermissionObserver::class);
        \App\Models\Poa\Poa::observe(\App\Observers\PoaObserver::class);
        Requisicion::observe(RequisicionObserver::class);
    }
}