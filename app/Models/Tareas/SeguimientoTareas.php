<?php

namespace App\Models\Tareas;
use App\Models\BaseModel;
use App\Models\Tareas\Tareas;
use App\Models\Actividad\Actividad;
use App\Models\Poa\PoaDeptos;
use App\Models\Presupuestos\Presupuestos;
use App\Models\MedioVerificacion\MedioVerificacion;

class SeguimientoTareas extends BaseModel
{
    protected $table = 'seguimiento_tareas';

    protected $fillable = [
        'seguimiento',
        'monto_ejecutado',
        'fecha',
        'idTarea',
        'idActividad',
        'idPoaDepto',
        'idPresupuesto',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function tarea()
    {
        return $this->belongsTo(Tareas::class, 'idTarea');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }

    public function poaDepto()
    {
        return $this->belongsTo(PoaDeptos::class, 'idPoaDepto');
    }

    public function presupuesto()
    {
        return $this->belongsTo(Presupuestos::class, 'idPresupuesto');
    }

    public function mediosVerificacion()
    {
        return $this->hasMany(MedioVerificacion::class, 'idSeguimiento');
    }
}