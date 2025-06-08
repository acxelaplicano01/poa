<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $module
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module = null)
    {
        // Si no se especifica un módulo, continuar
        if (!$module) {
            return $next($request);
        }

        $user = auth()->user();
        
        // Si no hay usuario autenticado, redirigir al login
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Verificar si es super-admin (siempre tiene acceso)
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }
        
        // Verificar permiso específico para acceder al módulo (formato: acceso-modulo)
        $modulePermission = "acceso-{$module}";
        if (!$user->can($modulePermission)) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a este módulo.');
        }

        return $next($request);
    }
}