<?php

namespace App\Models\Planificacion;
use App\Models\BaseModel;
use App\Models\Archivos\Archivo;
use App\Models\Actividad\Actividad;
use App\Models\Planificacion\Planificacion;
use App\Models\Planificacion\SeguimientoPlanificacion;

class MedioVerificacionPlanificacion extends BaseModel
{
    protected $table = 'medio_verificacion_planificacion';

    protected $fillable = [
        'observacion',
        'idArchivo',
        'idActividad',
        'idPlanificacion',
        'idSeguimiento',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function archivo()
    {
        return $this->belongsTo(Archivo::class, 'idArchivo');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }

    public function planificacion()
    {
        return $this->belongsTo(Planificacion::class, 'idPlanificacion');
    }

    public function seguimiento()
    {
        return $this->belongsTo(SeguimientoPlanificacion::class, 'idSeguimiento');
    }
}