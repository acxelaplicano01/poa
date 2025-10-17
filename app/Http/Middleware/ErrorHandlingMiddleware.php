<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;

class ErrorHandlingMiddleware
{
    /**
     * Handle an incoming request and catch any exceptions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Procesa la solicitud normalmente
            $response = $next($request);
            return $response;
        } catch (ValidationException $e) {
            // Manejo específico para errores de validación
            return $this->handleValidationException($request, $e);
        } catch (AuthenticationException $e) {
            // Manejo específico para errores de autenticación
            return $this->handleAuthenticationException($request, $e);
        } catch (AuthorizationException $e) {
            // Manejo específico para errores de autorización
            return $this->handleAuthorizationException($request, $e);
        } catch (NotFoundHttpException $e) {
            // Manejo específico para recursos no encontrados
            return $this->handleNotFoundException($request, $e);
        } catch (QueryException $e) {
            // Manejo específico para errores de base de datos
            return $this->handleDatabaseException($request, $e);
        } catch (Throwable $e) {
            // Manejo genérico para cualquier otro tipo de error
            return $this->handleGenericException($request, $e);
        }
    }

    /**
     * Maneja errores de validación.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  ValidationException  $e
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected function handleValidationException(Request $request, ValidationException $e)
    {
        $this->logException($e, 'Validation Error', 'warning', $request);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors(),
                'status' => 'error'
            ], 422);
        }

        // Para solicitudes web normales, redirige con los errores
        return redirect()->back()
            ->withInput($request->except(['password', 'password_confirmation']))
            ->withErrors($e->errors());
    }

    /**
     * Maneja errores de autenticación.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  AuthenticationException  $e
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected function handleAuthenticationException(Request $request, AuthenticationException $e)
    {
        $this->logException($e, 'Authentication Error', 'warning', $request);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'No autenticado.',
                'status' => 'error'
            ], 401);
        }

        // Para solicitudes web normales, redirige a login
        return redirect()->guest(route('login'))
            ->with('error', 'Necesitas iniciar sesión para acceder a esta página.');
    }

    /**
     * Maneja errores de autorización.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  AuthorizationException  $e
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected function handleAuthorizationException(Request $request, AuthorizationException $e)
    {
        $this->logException($e, 'Authorization Error', 'warning', $request);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'No tienes permiso para realizar esta acción.',
                'status' => 'error'
            ], 403);
        }

        // Para solicitudes web normales
        return redirect()->back()
            ->with('error', 'No tienes permiso para realizar esta acción.');
    }

    /**
     * Maneja errores de recurso no encontrado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  NotFoundHttpException  $e
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected function handleNotFoundException(Request $request, NotFoundHttpException $e)
    {
        $this->logException($e, 'Resource Not Found', 'warning', $request);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'El recurso solicitado no existe.',
                'status' => 'error'
            ], 404);
        }

        // Para solicitudes web normales
        return redirect()->route('error.404')
            ->with('error', 'El recurso solicitado no existe.');
    }

    /**
     * Maneja errores de base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  QueryException  $e
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected function handleDatabaseException(Request $request, QueryException $e)
    {
        $this->logException($e, 'Database Error', 'error', $request);

        // Determinar si es un error de clave duplicada, restricción, etc.
        $errorMessage = 'Error al procesar la operación en la base de datos.';
        
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            $errorMessage = 'Ya existe un registro con esa información.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $errorMessage,
                'status' => 'error',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }

        // Para solicitudes web normales
        return redirect()->back()
            ->withInput($request->except(['password', 'password_confirmation']))
            ->with('error', $errorMessage);
    }

    /**
     * Maneja cualquier otro tipo de excepción genérica.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Throwable  $e
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected function handleGenericException(Request $request, Throwable $e)
    {
        $this->logException($e, 'General Error', 'error', $request);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Ha ocurrido un error en el servidor.',
                'status' => 'error',
                'details' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                ] : null
            ], 500);
        }

        // Para solicitudes web normales
        return redirect()->route('error.500')
            ->with('error', 'Ha ocurrido un error en el servidor.');
    }

    /**
     * Registra la excepción en el log.
     *
     * @param  Throwable  $e
     * @param  string  $title
     * @param  string  $level
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function logException(Throwable $e, string $title, string $level, Request $request)
    {
        $logData = [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'request' => [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'input' => $request->except(['password', 'password_confirmation']),
                'headers' => $request->header(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
            'user' => auth()->check() ? [
                'id' => auth()->id(),
                'email' => auth()->user()->email,
            ] : 'Guest'
        ];

        Log::{$level}("{$title}: " . $e->getMessage(), $logData);
    }
}