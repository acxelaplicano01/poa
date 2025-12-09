<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmpleado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (auth()->check()) {
            $user = auth()->user();
            
            // Si no tiene empleado asignado y no está en la ruta de registro de empleado
            if (!$user->idEmpleado && !$request->routeIs('empleado.registrar')) {
                return redirect()->route('empleado.registrar');
            }
            
            // Si ya tiene empleado y está intentando acceder a la ruta de registro
            if ($user->idEmpleado && $request->routeIs('empleado.registrar')) {
                return redirect()->route('dashboard');
            }
        }
        
        return $next($request);
    }
}
