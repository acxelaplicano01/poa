<?php

namespace App\Models\Tareas;
use App\Models\BaseModel;
use App\Models\Actividad\Actividad;
use App\Models\Poa\Poa;
use App\Models\Departamento\Departamento;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Empleados\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends BaseModel
{
    protected $table = 'tareas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'correlativo',
        'estado',
        'isPresupuesto',
        'idActividad',
        'idPoa',
        'idDeptartamento',
        'idUE',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }

    public function poa()
    {
        return $this->belongsTo(Poa::class, 'idPoa');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'idDeptartamento');
    }

    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUE');
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_tareas', 'idTarea', 'idEmpleado')
            ->withTimestamps()
            ->withPivot(['idActividad', 'created_by', 'updated_by', 'deleted_by']);
    }
}