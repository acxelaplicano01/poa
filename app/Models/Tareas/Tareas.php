<?php

namespace App\Models\Tareas;
use App\Models\BaseModel;
use App\Models\Actividad\Actividad;
use App\Models\Poa\Poas;
use App\Models\Departamento\Departamentos;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Empleados\Empleados;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tareas extends BaseModel
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
        return $this->belongsTo(Poas::class, 'idPoa');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamentos::class, 'idDeptartamento');
    }

    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUE');
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleados::class, 'empleado_tareas', 'idTarea', 'idEmpleado')
            ->withTimestamps()
            ->withPivot(['idActividad', 'created_by', 'updated_by', 'deleted_by']);
    }
}