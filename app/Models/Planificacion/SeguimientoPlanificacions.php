<?php

namespace App\Models\Planificacion;
use App\Models\BaseModel;
use App\Models\Planificacion\Planificacion;
use App\Models\Actividad\Actividad;
use App\Models\Poa\PoaDeptos;
use App\Models\Planificacion\MedioVerificacionPlanificacion;

class SeguimientoPlanificacion extends BaseModel
{
    protected $table = 'seguimiento_planificacions';

    protected $fillable = [
        'seguimiento',
        'ejecutado',
        'fecha',
        'idPlanificacion',
        'idActividad',
        'idPoaDepto',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function planificacion()
    {
        return $this->belongsTo(Planificacion::class, 'idPlanificacion');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }

    public function poaDepto()
    {
        return $this->belongsTo(PoaDeptos::class, 'idPoaDepto');
    }

    public function mediosVerificacion()
    {
        return $this->hasMany(MedioVerificacionPlanificacion::class, 'idSeguimiento');
    }
}