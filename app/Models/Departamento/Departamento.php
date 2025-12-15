<?php

namespace App\Models\Departamento;
use App\Models\BaseModel;
use App\Models\Empleados\Empleado;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Actividad\Actividad;

class Departamento extends BaseModel
{
    protected $table = 'departamentos';

    protected $fillable = [
        'name',
        'siglas',
        'estructura',
        'tipo',
        'idUnidadEjecutora',
    ];

    // Relación con UnidadEjecutora
    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUnidadEjecutora');
    }

    // Corregir la relación con empleados - debe coincidir con la del modelo Empleado
    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_deptos', 'idDepto', 'idEmpleado')
                    ->withTimestamps()
                    ->withPivot(['created_by', 'updated_by', 'deleted_by']);
    }

    // Relación con actividades (para revisiones)
    public function actividades()
    {
        return $this->hasMany(\App\Models\Actividad\Actividad::class, 'idDeptartamento');
    }
}