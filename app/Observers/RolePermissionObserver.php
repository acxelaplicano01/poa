<?php
namespace App\Observers;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RolePermissionObserver
{
    /**
     * Handle the Role "updated" event.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        $this->syncModulePermissions($role);
    }

    /**
     * Handle the Role "created" event.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        $this->syncModulePermissions($role);
    }

    /**
     * Sincroniza los permisos de módulos y sus subpermisos
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return void
     */
    private function syncModulePermissions(Role $role)
    {
        // Obtener permisos actuales del rol
        $currentPermissions = $role->permissions->pluck('name')->toArray();
        
        // Obtener todos los permisos específicos de módulos
        $allPermissions = \Spatie\Permission\Models\Permission::all()->pluck('name');
        
        // Encontrar los permisos de acceso a módulos que NO tiene el rol
        $missingModuleAccess = $allPermissions->filter(function ($permission) use ($currentPermissions) {
            return Str::startsWith($permission, 'acceso-') && !in_array($permission, $currentPermissions);
        })->toArray();
        
        // Para cada módulo al que no tiene acceso
        foreach ($missingModuleAccess as $moduleAccess) {
            $module = str_replace('acceso-', '', $moduleAccess);
            
            // Identificar los permisos específicos de ese módulo que deben ser removidos
            $moduleSpecificPermissions = $allPermissions->filter(function ($permission) use ($module) {
                return Str::startsWith($permission, $module . '.');
            })->toArray();
            
            // Remover estos permisos del rol
            foreach ($moduleSpecificPermissions as $permission) {
                if (in_array($permission, $currentPermissions)) {
                    $role->revokePermissionTo($permission);
                }
            }
        }
        
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}