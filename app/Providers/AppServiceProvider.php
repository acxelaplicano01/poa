<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

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

        // Define el Gate personalizado para verificar acceso a módulos
        Gate::define('acceder-modulo', function ($user, $module) {
            // Verificar si el usuario tiene permiso específico para este módulo
            if ($user->hasPermissionTo('acceso-' . $module)) {
                return true;
            }

            // O verificar si tiene algún permiso relacionado con el módulo
            $permissions = $user->getAllPermissions()->pluck('name')->toArray();
            foreach ($permissions as $permission) {
                if (strpos($permission, $module . '.') === 0) {
                    return true;
                }
            }

            return false;
        });
    }
}