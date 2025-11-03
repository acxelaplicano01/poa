<?php

namespace App\Models\Empleados;
use App\Models\BaseModel;
use App\Models\User;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Departamento\Departamento;

class Empleado extends BaseModel
{
    protected $table = 'empleados';

    protected $fillable = [
        'dni',//
        'num_empleado',
        'nombre',
        'apellido',
        'direccion',
        'telefono',
        'fechaNacimiento',
        'sexo',
        'idUnidadEjecutora',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con usuarios (un empleado puede tener uno o varios usuarios)
    public function users()
    {
        return $this->hasMany(User::class, 'idEmpleado');
    }
    
    // Relación con el usuario principal (si solo tiene uno)
    public function user()
    {
        return $this->hasOne(User::class, 'idEmpleado');
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