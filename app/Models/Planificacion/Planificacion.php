<?php

namespace App\Models\Planificacion;
use App\Models\BaseModel;
use App\Models\Actividad\Actividad;
use App\Models\Actividad\Indicador;
use App\Models\Mes\Mes;
use App\Models\Planificacion\SeguimientoPlanificacion;
use App\Models\Planificacion\MedioVerificacionPlanificacion;

class Planificacion extends BaseModel
{
    protected $table = 'planificacions';

    protected $fillable = [
        'cantidad',
        'fechaInicio',
        'fechaFin',
        'idActividad',
        'idIndicador',
        'idMes',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }

    public function indicador()
    {
        return $this->belongsTo(Indicador::class, 'idIndicador');
    }

    public function mes()
    {
        return $this->belongsTo(Mes::class, 'idMes');
    }

    public function seguimientos()
    {
        return $this->hasMany(SeguimientoPlanificacion::class, 'idPlanificacion');
    }

    public function mediosVerificacion()
    {
        return $this->hasMany(MedioVerificacionPlanificacion::class, 'idPlanificacion');
    }
}