<?php

namespace App\Models\Empleados;
use App\Models\BaseModel;
use App\Models\User;
use App\Models\UnidadesEjecutora\UnidadEjecutora;
use App\Models\Departamento\Departamentos;

class Empleados extends BaseModel
{
    protected $table = 'empleados';

    protected $fillable = [
        'dni',
        'num_empleado',
        'nombre',
        'apellido',
        'direccion',
        'telefono',
        'fechaNacimiento',
        'sexo',
        'user_id',
        'idUnidadEjecutora',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con Unidad Ejecutora
    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUnidadEjecutora');
    }

    // Relación con departamentos (muchos a muchos a través de empleado_deptos)
    public function departamentos()
    {
        return $this->belongsToMany(Departamento::class, 'empleado_deptos', 'idEmpleado', 'idDepto')
                    ->withTimestamps()
                    ->withPivot(['created_by', 'updated_by', 'deleted_by']);
    }
}