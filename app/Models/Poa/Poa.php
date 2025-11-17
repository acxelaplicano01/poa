<?php

namespace App\Models\Poa;
use App\Models\BaseModel;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Instituciones\Institucion;
use App\Models\Poa\PoaDepto;
use App\Models\TechoUes\TechoUe;


class Poa extends BaseModel
{
    protected $table = 'poas';

    protected $fillable = [
        'name',
        'anio',
        'activo',
        'idInstitucion',
        'idUE',
        // Los campos de auditoría ya están en BaseModel
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Scope para buscar POAs activos
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    // Relación con Institucion
    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'idInstitucion');
    }

    // Relación con Unidad Ejecutora
    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUE');
    }

    // Relación con PoaDeptos
    public function poaDeptos()
    {
        return $this->hasMany(PoaDepto::class, 'idPoa');
    }

    // Relación con TechoUe
    public function techoUe()
    {
        return $this->hasOne(TechoUe::class, 'idPoa');
    }

    // Relación con múltiples TechoUes
    public function techoUes()
    {
        return $this->hasMany(TechoUe::class, 'idPoa');
    }

    // Relación con TechoDeptos
    public function techoDeptos()
    {
        return $this->hasMany(\App\Models\TechoUes\TechoDepto::class, 'idPoa');
    }

    // Relación con Plazos
    public function plazos()
    {
        return $this->hasMany(\App\Models\Plazos\PlazoPoa::class, 'idPoa');
    }

        /**
     * Verifica si se puede realizar una acción específica en base a los plazos configurados
     * @param string $tipoPlazo Tipo de plazo (asignacion_nacional, asignacion_departamental, planificacion, seguimiento, requerimientos)
     * @return bool
     */
    public function puedeRealizarAccion($tipoPlazo)
    {
        // Primero verificar si el POA está activo
        if (!$this->activo) {
            return false;
        }

        // Verificar si existe un plazo vigente para este tipo de acción
        // Solo buscar plazos estándar (sin nombre_plazo) o personalizados activos del mismo tipo
        return $this->plazos()
            ->where('tipo_plazo', $tipoPlazo)
            ->where('activo', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->exists();
    }

    /**
     * Verifica si se puede asignar presupuesto nacional
     */
    public function puedeAsignarPresupuestoNacional()
    {
        return $this->puedeRealizarAccion('asignacion_nacional');
    }

    /**
     * Verifica si se puede asignar presupuesto departamental
     */
    public function puedeAsignarPresupuestoDepartamental()
    {
        return $this->puedeRealizarAccion('asignacion_departamental');
    }

    /**
     * Verifica si se puede planificar actividades
     */
    public function puedePlanificar()
    {
        return $this->puedeRealizarAccion('planificacion');
    }

    /**
     * Verifica si se pueden crear requerimientos
     */
    public function puedeRequerir()
    {
        return $this->puedeRealizarAccion('requerimientos');
    }

    /**
     * Verifica si se puede realizar seguimiento
     */
    public function puedeSeguimiento()
    {
        return $this->puedeRealizarAccion('seguimiento');
    }

    /**
     * Obtiene el mensaje de error cuando no se puede realizar una acción
     * 
     * @param string $tipoPlazo
     * @return string
     */
    public function getMensajeErrorPlazo($tipoPlazo)
    {
        if (!$this->activo) {
            return 'El POA está inactivo. No se pueden realizar acciones sobre él.';
        }

        // Primero buscar plazo activo
        $plazo = $this->plazos()
            ->where('tipo_plazo', $tipoPlazo)
            ->where('activo', true)
            ->first();

        // Si no hay plazo activo, buscar cualquier plazo de este tipo (incluso inactivos)
        if (!$plazo) {
            $plazo = $this->plazos()
                ->where('tipo_plazo', $tipoPlazo)
                ->orderBy('fecha_fin', 'desc') // Obtener el más reciente
                ->first();
        }

        // Si definitivamente no existe ningún plazo configurado
        if (!$plazo) {
            return 'No hay un plazo configurado para esta acción.';
        }

        $hoy = now();
        
        // Plazo aún no ha iniciado
        if ($hoy < $plazo->fecha_inicio) {
            return 'El plazo para esta acción aún no ha iniciado. Inicia el ' . \Carbon\Carbon::parse($plazo->fecha_inicio)->format('d/m/Y') . '.';
        }

        // Plazo vencido
        if ($hoy > $plazo->fecha_fin) {
            $diasVencido = (int) \Carbon\Carbon::parse($plazo->fecha_fin)->diffInDays($hoy);
            $nombrePlazo = $plazo->nombre_plazo ?: $this->getNombreTipoPlazo($tipoPlazo);
            return 'El plazo de ' . $nombrePlazo . ' venció hace ' . $diasVencido . ($diasVencido == 1 ? ' día' : ' días') . ' (el ' . \Carbon\Carbon::parse($plazo->fecha_fin)->format('d/m/Y') . ').';
        }

        return 'No se puede realizar esta acción en este momento.';
    }

    /**
     * Obtiene el nombre legible del tipo de plazo
     */
    private function getNombreTipoPlazo($tipoPlazo)
    {
        $nombres = [
            'asignacion_nacional' => 'asignación nacional',
            'asignacion_departamental' => 'asignación departamental',
            'planificacion' => 'planificación',
            'requerimientos' => 'requerimientos',
            'seguimiento' => 'seguimiento',
        ];

        return $nombres[$tipoPlazo] ?? 'esta acción';
    }

    /**
     * Obtiene los días restantes para un tipo de plazo específico
     * Retorna null si no hay plazo o no está vigente
     * Prioriza plazos vigentes (entre fecha inicio y fin)
     */
    public function getDiasRestantes($tipoPlazo)
    {
        if (!$this->activo) {
            return null;
        }

        $hoy = now()->startOfDay();

        // Buscar plazos activos del tipo especificado (estándar o personalizado)
        $plazos = $this->plazos()
            ->where('tipo_plazo', $tipoPlazo)
            ->where('activo', true)
            ->get();

        if ($plazos->isEmpty()) {
            return null;
        }

        // Filtrar y priorizar plazos vigentes
        $plazoVigente = $plazos->filter(function($p) use ($hoy) {
            $inicio = \Carbon\Carbon::parse($p->fecha_inicio)->startOfDay();
            $fin = \Carbon\Carbon::parse($p->fecha_fin)->startOfDay();
            // Un plazo está vigente si hoy está entre inicio y fin
            return $hoy->between($inicio, $fin);
        })->sortByDesc(function($p) {
            // Ordenar por fecha fin más lejana
            return \Carbon\Carbon::parse($p->fecha_fin);
        })->first();

        // Si no hay plazo vigente, buscar próximos plazos (que aún no han iniciado)
        if (!$plazoVigente) {
            $plazoVigente = $plazos->filter(function($p) use ($hoy) {
                $inicio = \Carbon\Carbon::parse($p->fecha_inicio)->startOfDay();
                return $hoy->lt($inicio);
            })->sortBy(function($p) {
                // Ordenar por fecha inicio más cercana
                return \Carbon\Carbon::parse($p->fecha_inicio);
            })->first();
        }

        // Si no hay plazos vigentes ni próximos, usar el último que venció
        if (!$plazoVigente) {
            $plazoVigente = $plazos->sortByDesc(function($p) {
                return \Carbon\Carbon::parse($p->fecha_fin);
            })->first();
        }

        if (!$plazoVigente) {
            return null;
        }

        $fechaFin = \Carbon\Carbon::parse($plazoVigente->fecha_fin)->startOfDay();
        $fechaInicio = \Carbon\Carbon::parse($plazoVigente->fecha_inicio)->startOfDay();

        // Si el plazo no ha iniciado, retornar días hasta el inicio (negativo)
        if ($hoy < $fechaInicio) {
            return -1 * (int) $hoy->diffInDays($fechaInicio);
        }

        // Si el plazo ya venció, retornar 0
        if ($hoy > $fechaFin) {
            return 0;
        }

        // Retornar días restantes (incluyendo el día actual)
        return (int) $hoy->diffInDays($fechaFin) + 1;
    }

    /**
     * Obtiene los días restantes para asignación nacional
     */
    public function getDiasRestantesAsignacionNacional()
    {
        return $this->getDiasRestantes('asignacion_nacional');
    }

    /**
     * Obtiene los días restantes para planificación
     */
    public function getDiasRestantesPlanificacion()
    {
        return $this->getDiasRestantes('planificacion');
    }

    /**
     * Obtiene los días restantes para asignación departamental
     */
    public function getDiasRestantesAsignacionDepartamental()
    {
        return $this->getDiasRestantes('asignacion_departamental');
    }

    /**
     * Obtiene los días restantes para requerimientos
     */
    public function getDiasRestantesRequerimientos()
    {
        return $this->getDiasRestantes('requerimientos');
    }

    /**
     * Obtiene los días restantes para seguimiento
     */
    public function getDiasRestantesSeguimiento()
    {
        return $this->getDiasRestantes('seguimiento');
    }
}
