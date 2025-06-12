<?php

namespace App\Models\Empleados;
use App\Models\BaseModel;
use App\Models\Empleados\Empleado;
use App\Models\Departamento\Departamento;

class EmpleadoDepto extends BaseModel
{
    protected $table = 'empleado_deptos';

    protected $fillable = [
        'idEmpleado',
        'idDepto',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'idDepto');
    }
}