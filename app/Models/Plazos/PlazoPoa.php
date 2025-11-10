<?php

namespace App\Models\Plazos;

use App\Models\BaseModel;
use App\Models\Poa\Poa;
use Carbon\Carbon;

class PlazoPoa extends BaseModel
{
    protected $table = 'plazos_poa';

    protected $fillable = [
        'tipo_plazo',
        'nombre_plazo',
        'fecha_inicio',
        'fecha_fin',
        'idPoa',
        'activo',
        'descripcion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
    ];

    // Constantes para tipos de plazo
    const TIPO_ASIGNACION_NACIONAL = 'asignacion_nacional';
    const TIPO_ASIGNACION_DEPARTAMENTAL = 'asignacion_departamental';
    const TIPO_PLANIFICACION = 'planificacion';
    const TIPO_REQUERIMIENTOS = 'requerimientos';
    const TIPO_SEGUIMIENTO = 'seguimiento';

    // Relación con POA
    public function poa()
    {
        return $this->belongsTo(Poa::class, 'idPoa');
    }

    // Scope para buscar plazos activos
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    // Scope para buscar por tipo de plazo
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_plazo', $tipo);
    }

    // Scope para plazos vigentes (activos y dentro del rango de fechas)
    public function scopeVigente($query)
    {
        $hoy = Carbon::now()->format('Y-m-d');
        return $query->where('activo', true)
                    ->where('fecha_inicio', '<=', $hoy)
                    ->where('fecha_fin', '>=', $hoy);
    }

    // Verificar si el plazo está vigente actualmente
    public function estaVigente()
    {
        $hoy = Carbon::now();
        return $this->activo && 
               $hoy->greaterThanOrEqualTo($this->fecha_inicio) && 
               $hoy->lessThanOrEqualTo($this->fecha_fin);
    }

    // Verificar si el plazo ya venció
    public function haVencido()
    {
        return Carbon::now()->greaterThan($this->fecha_fin);
    }

    // Verificar si el plazo aún no ha comenzado
    public function esProximo()
    {
        return Carbon::now()->lessThan($this->fecha_inicio);
    }

    // Días restantes del plazo
    public function diasRestantes()
    {
        if ($this->haVencido()) {
            return 0;
        }
        
        if ($this->esProximo()) {
            return null; // Aún no inicia
        }
        
        $hoy = Carbon::now()->startOfDay();
        $fechaFin = Carbon::parse($this->fecha_fin)->startOfDay();
        
        return (int) $hoy->diffInDays($fechaFin) + 1;
    }

    // Obtener etiqueta del tipo de plazo
    public function getTipoPlazoLabelAttribute()
    {
        // Si hay un nombre personalizado, usarlo
        if ($this->nombre_plazo) {
            return $this->nombre_plazo;
        }
        
        // Si no, usar el label por defecto del tipo
        $labels = [
            self::TIPO_ASIGNACION_NACIONAL => 'Asignación Nacional',
            self::TIPO_ASIGNACION_DEPARTAMENTAL => 'Asignación Departamental',
            self::TIPO_PLANIFICACION => 'Planificación',
            self::TIPO_REQUERIMIENTOS => 'Requerimientos',
            self::TIPO_SEGUIMIENTO => 'Seguimiento',
        ];

        return $labels[$this->tipo_plazo] ?? $this->tipo_plazo;
    }

    // Obtener estado del plazo
    public function getEstadoAttribute()
    {
        if (!$this->activo) {
            return 'inactivo';
        }
        
        if ($this->estaVigente()) {
            return 'vigente';
        }
        
        if ($this->haVencido()) {
            return 'vencido';
        }
        
        return 'proximo';
    }

    // Método estático para verificar si un tipo de plazo está vigente para un POA
    public static function tipoVigente($idPoa, $tipo)
    {
        return self::where('idPoa', $idPoa)
                  ->where('tipo_plazo', $tipo)
                  ->vigente()
                  ->exists();
    }
}
