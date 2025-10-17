<?php
// filepath: c:\Users\acxel\Desktop\Desarrollo\Git Repos\POA\app\Services\LogService.php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogService
{
    
    /**
     * Registra una actividad en el sistema con nivel específico
     *
     * @param string $action La acción realizada
     * @param string $module El módulo donde se realizó la acción
     * @param string|null $description Descripción de la actividad
     * @param array $data Datos adicionales
     * @param string $level Nivel del log (info, warning, error, etc)
     * @return void
     */
    public static function activity(
        string $action, 
        string $module, 
        string $description = null, 
        array $data = [],
        string $level = 'info'
    ) {
        try {
            $user = Auth::user();
            $userId = $user ? $user->id : null;
            $userName = $user ? $user->name : 'Sistema';

            // Validar que el nivel sea válido
            $validLevels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];
            $level = in_array($level, $validLevels) ? $level : 'info';

            $logData = [
                'user_id' => $userId,
                'user_name' => $userName,
                'ip_address' => request()->ip(),
                'module' => $module,
                'action' => $action,
                'description' => $description,
                'data' => $data,
                'level' => $level
            ];

            // Registrar en archivo de log para depuración
            Log::debug("Intentando registrar actividad de nivel {$level}", $logData);

            // Registrar en base de datos
            $log = ActivityLog::create($logData);

            // Verificar si se creó correctamente
            Log::debug("Log creado con ID: " . ($log->id ?? 'null'));

            // Registrar en archivo de log usando el nivel especificado
            $message = "[$module] $userName realizó acción '$action': $description";
            Log::channel('activity')->{$level}($message, $logData);

            // Registrar en archivo de log de Laravel
            Log::{$level}($message, $logData);

        } catch (\Exception $e) {
            // Registrar el error para depuración
            Log::error("Error al registrar actividad: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            throw $e; // Re-lanzar la excepción para manejo superior
        }
    }
}