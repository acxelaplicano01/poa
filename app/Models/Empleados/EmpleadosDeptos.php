<?php

namespace App\Models\Empleados;
use App\Models\BaseModel;
use App\Models\Empleados\Empleados;
use App\Models\Departamento\Departamentos;

class EmpleadoDeptos extends BaseModel
{
    protected $table = 'empleado_deptos';

    protected $fillable = [
        'idEmpleado',
        'idDepto',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'idEmpleado');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamentos::class, 'idDepto');
    }
}