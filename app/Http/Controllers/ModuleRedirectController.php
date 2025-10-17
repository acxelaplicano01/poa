<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModuleRedirectController extends Controller
{
    public function redirectToModule($module)
    {
        $user = auth()->user();
        $moduleConfig = config('rutas.' . $module, []);
        
        // Si el módulo no existe, redireccionar al dashboard
        if (empty($moduleConfig)) {
            return redirect()->route('dashboard');
        }
        
        // Si el usuario es super-admin, ir a la ruta principal del módulo
        if ($user->hasRole('super-admin')) {
            return redirect()->route($moduleConfig['route']);
        }
        
        // Verificar si tiene acceso general al módulo
        $modulePermission = "acceso-{$module}";
        $hasModuleAccess = $user->can($modulePermission);
        
        // Si no hay ítems en el módulo, usar la ruta principal
        if (!isset($moduleConfig['items']) || empty($moduleConfig['items'])) {
            if ($hasModuleAccess) {
                return redirect()->route($moduleConfig['route']);
            } else {
                return redirect()->route('dashboard')->with('error', 'No tienes acceso a este módulo.');
            }
        }
        
        // Buscar un ítem al que tenga acceso
        foreach ($moduleConfig['items'] as $item) {
            // Si es always_visible o no tiene permisos específicos
            if (
                (isset($item['always_visible']) && $item['always_visible']) ||
                (!isset($item['permisos']) || empty($item['permisos']))
            ) {
                return redirect()->route($item['route']);
            }
            
            // Verificar permisos específicos
            if (isset($item['permisos']) && is_array($item['permisos'])) {
                foreach ($item['permisos'] as $permiso) {
                    if ($user->can($permiso)) {
                        return redirect()->route($item['route']);
                    }
                }
            }
        }
        
        // Si no encontró ninguna ruta con acceso, redireccionar al dashboard con mensaje
        return redirect()->route('dashboard')->with('error', 'No tienes acceso a ninguna sección de este módulo.');
    }
}