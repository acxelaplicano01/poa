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
     * Verifica si el POA está activo y tiene un plazo vigente para un tipo específico
     * 
     * @param string $tipoPlazo Tipo de plazo a verificar (asignacion_departamental, planificacion, etc.)
     * @return bool
     */
    public function puedeRealizarAccion($tipoPlazo)
    {
        // Primero verificar si el POA está activo
        if (!$this->activo) {
            return false;
        }

        // Verificar si existe un plazo vigente para este tipo de acción
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

        $plazo = $this->plazos()
            ->where('tipo_plazo', $tipoPlazo)
            ->where('activo', true)
            ->first();

        if (!$plazo) {
            return 'No hay un plazo configurado para esta acción.';
        }

        $hoy = now();
        
        if ($hoy < $plazo->fecha_inicio) {
            return 'El plazo para esta acción aún no ha iniciado. Inicia el ' . \Carbon\Carbon::parse($plazo->fecha_inicio)->format('d/m/Y') . '.';
        }

        if ($hoy > $plazo->fecha_fin) {
            return 'El plazo para esta acción ha vencido. Venció el ' . \Carbon\Carbon::parse($plazo->fecha_fin)->format('d/m/Y') . '.';
        }

        return 'No se puede realizar esta acción en este momento.';
    }

    /**
     * Obtiene los días restantes para un tipo de plazo específico
     * Retorna null si no hay plazo o no está vigente
     */
    public function getDiasRestantes($tipoPlazo)
    {
        if (!$this->activo) {
            return null;
        }

        $plazo = $this->plazos()
            ->where('tipo_plazo', $tipoPlazo)
            ->where('activo', true)
            ->first();

        if (!$plazo) {
            return null;
        }

        $hoy = now()->startOfDay();
        $fechaFin = \Carbon\Carbon::parse($plazo->fecha_fin)->startOfDay();
        $fechaInicio = \Carbon\Carbon::parse($plazo->fecha_inicio)->startOfDay();

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
