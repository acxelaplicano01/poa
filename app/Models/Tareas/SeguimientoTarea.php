<?php

namespace App\Models\Tareas;
use App\Models\BaseModel;
use App\Models\Tareas\Tarea;
use App\Models\Actividad\Actividad;
use App\Models\Poa\PoaDepto;
use App\Models\Presupuestos\Presupuesto;
use App\Models\MedioVerificacion\MedioVerificacion;

class SeguimientoTarea extends BaseModel
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
        return $this->belongsTo(Tarea::class, 'idTarea');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }

    public function poaDepto()
    {
        return $this->belongsTo(PoaDepto::class, 'idPoaDepto');
    }

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'idPresupuesto');
    }

    public function mediosVerificacion()
    {
        return $this->hasMany(MedioVerificacion::class, 'idSeguimiento');
    }
}