<?php

namespace App\Models\Empleados;
use App\Models\BaseModel;
use App\Models\Empleados\Empleado;
use App\Models\Actividad\Actividad;

class EmpleadoActividad extends BaseModel
{
    protected $table = 'empleado_actividads';

    protected $fillable = [
        'descripcion',
        'idEmpleado',
        'idActividad',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }
}