<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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

        // Verificar acceso usando el Gate definido
        if (!auth()->user()->can('acceder-modulo', $module)) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a este módulo.');
        }

        return $next($request);
    }
}