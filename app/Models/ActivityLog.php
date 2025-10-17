<?php
// filepath: c:\Users\acxel\Desktop\Desarrollo\Git Repos\POA\app\Models\ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'ip_address',
        'module',
        'action',
        'description',
        'data',
        'level',
    ];

     public const LEVELS = [
        'emergency' => 'Emergencia',
        'alert'     => 'Alerta',
        'critical'  => 'Crítico',
        'error'     => 'Error',
        'warning'   => 'Advertencia',
        'notice'    => 'Aviso',
        'info'      => 'Información',
        'debug'     => 'Depuración'
    ];
    
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Filtro por nivel de log
     */
    public function scopeOfLevel(Builder $query, string $level): Builder
    {
        return $query->where('level', $level);
    }

    /**
     * Filtro por módulo
     */
    public function scopeInModule(Builder $query, string $module): Builder
    {
        return $query->where('module', $module);
    }

    /**
     * Filtro por acción
     */
    public function scopeWithAction(Builder $query, string $action): Builder
    {
        return $query->where('action', $action);
    }

    /**
     * Filtro por usuario
     */
    public function scopeByUser(Builder $query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Filtro por fecha
     */
    public function scopeInDateRange(Builder $query, $startDate, $endDate = null): Builder
    {
        if ($endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        return $query->whereDate('created_at', $startDate);
    }

    /**
     * Obtener un color según el nivel del log
     */
    public function getLevelColorAttribute(): string
    {
        return [
            'emergency' => 'rose-700',
            'alert'     => 'rose-600',
            'critical'  => 'red-600',
            'error'     => 'red-500',
            'warning'   => 'amber-500',
            'notice'    => 'blue-500',
            'info'      => 'emerald-500',
            'debug'     => 'gray-500',
        ][$this->level] ?? 'gray-700';
    }

    /**
     * Obtener un icono según la acción del log
     */
    public function getActionIconAttribute(): string
    {
        return [
            'crear' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />',
            'actualizar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />',
            'eliminar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />',
            'acceso' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />',
            'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        ][$this->action] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
    }
}