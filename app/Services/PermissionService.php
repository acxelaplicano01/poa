<?php
namespace App\Services;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionService
{
    /**
     * Asigna o revoca permisos de módulos y sus subpermisos.
     *
     * @param \App\Models\User|\Spatie\Permission\Models\Role $entity
     * @param string $modulePermission
     * @param bool $grant
     * @return void
     */
    public static function syncModulePermissions($entity, string $modulePermission, bool $grant = true)
    {
        // Verificamos si es un permiso de acceso a módulo
        if (strpos($modulePermission, 'acceso-') !== 0) {
            // Si no es permiso de módulo, simplemente asignamos/revocamos
            if ($grant) {
                $entity->givePermissionTo($modulePermission);
            } else {
                $entity->revokePermissionTo($modulePermission);
            }
            return;
        }

        // Extraer el nombre del módulo
        $module = str_replace('acceso-', '', $modulePermission);
        
        // Buscar todos los permisos relacionados con este módulo
        $relatedPermissions = Permission::where('name', 'like', $module . '.%')->get();
        
        if ($grant) {
            // Si estamos otorgando acceso al módulo, también otorgamos los subpermisos
            $entity->givePermissionTo($modulePermission);
            foreach ($relatedPermissions as $permission) {
                $entity->givePermissionTo($permission->name);
            }
        } else {
            // Si revocamos acceso al módulo, revocamos todos los subpermisos
            $entity->revokePermissionTo($modulePermission);
            foreach ($relatedPermissions as $permission) {
                $entity->revokePermissionTo($permission->name);
            }
        }
        
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
    
    /**
     * Obtiene un mapa de permisos agrupados por módulo
     *
     * @return array
     */
    public static function getPermissionMap()
    {
        $permissions = Permission::all();
        $map = [];
        
        foreach ($permissions as $permission) {
            $name = $permission->name;
            
            // Permisos de módulos principales
            if (strpos($name, 'acceso-') === 0) {
                $module = str_replace('acceso-', '', $name);
                $map[$module] = [
                    'main' => $name,
                    'permissions' => []
                ];
            }
        }
        
        // Mapear sub-permisos a sus módulos
        foreach ($permissions as $permission) {
            $name = $permission->name;
            
            // Si es un sub-permiso (contiene un punto)
            if (strpos($name, '.') !== false) {
                list($module, $action) = explode('.', $name, 2);
                if (isset($map[$module])) {
                    $map[$module]['permissions'][] = $name;
                }
            }
        }
        
        return $map;
    }
}