<?php

namespace App\Models\Empleados;
use App\Models\BaseModel;
use App\Models\Empleados\Empleado;
use App\Models\Actividad\Actividad;
use App\Models\Tareas\Tarea;

class EmpleadoTarea extends BaseModel
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
        return $this->belongsTo(Empleado::class, 'idEmpleado');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'idTarea');
    }
}