<?php

namespace App\Models\Empleados;
use App\Models\BaseModel;
use App\Models\Empleados\Empleados;
use App\Models\Actividad\Actividad;
use App\Models\Tareas\Tareas;

class EmpleadoTareas extends BaseModel
{
    protected $table = 'empleado_tareas';

    protected $fillable = [
        'idEmpleado',
        'idActividad',
        'idTarea',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'idEmpleado');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }

    public function tarea()
    {
        return $this->belongsTo(Tareas::class, 'idTarea');
    }
}