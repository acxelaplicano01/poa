<?php
// filepath: c:\Users\acxel\Desktop\Desarrollo\Git Repos\POA\app\Http\Middleware\LogUserActivity.php

namespace App\Http\Middleware;

use Closure;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class LogUserActivity
{
    /**
     * Registra la actividad del usuario al acceder a rutas específicas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Procesar la petición primero
        $response = $next($request);
        
        // Solo registrar rutas con nombre y métodos GET (para no duplicar logs en formularios)
        if ($request->isMethod('get') && Route::current() && Route::current()->getName()) {
            $routeName = Route::current()->getName();
            
            // Determinar el módulo basado en la ruta
            $module = explode('.', $routeName)[0] ?? 'system';
            
            // Determinar la acción basada en la última parte de la ruta
            $routeParts = explode('.', $routeName);
            $action = end($routeParts);
            
            // Mapear tipos de acciones comunes
            $actionDescriptions = [
                'index' => 'listó registros',
                'show' => 'visualizó un registro',
                'create' => 'accedió al formulario de creación',
                'edit' => 'accedió al formulario de edición',
                'dashboard' => 'accedió al panel principal',
            ];
            
            $description = $actionDescriptions[$action] ?? 'accedió a ' . str_replace('.', ' / ', $routeName);
            
            // Registrar la actividad
            LogService::activity(
                'acceso', 
                ucfirst($module), 
                $description,
                ['route' => $routeName, 'url' => $request->fullUrl()]
            );
        }
        
        return $response;
    }
}