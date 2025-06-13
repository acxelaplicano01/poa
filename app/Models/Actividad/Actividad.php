<?php

namespace App\Models\Actividad;
use App\Models\BaseModel;
use App\Models\Actividad\TipoActividad;
use App\Models\Poa\Poas;
use App\Models\Departamento\Departamentos;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Resultados\Resultados;
use App\Models\Actividad\Indicadores;
use App\Models\Actividad\Eventos;
use App\Models\Actividad\Revisions;
use App\Models\Actividad\MedioVerificacionActividad;
use App\Models\Empleados\Empleados;

class Actividad extends BaseModel
{
    protected $table = 'actividads';

    protected $fillable = [
        'nombre',
        'descripcion',
        'correlativo',
        'resultadoActividad',
        'poblacion_objetivo',
        'medio_verificacion',
        'estado',
        'finalizada',
        'uploadedIntoSPI',
        'idPoa',
        'idPoaDepto',
        'idInstitucion',
        'idDeptartamento',
        'idUE',
        'idTipo',
        'idResultado',
        'idCategoria',
        'finalizada_at',
        'finalizada_by',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoActividad::class, 'idTipo');
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

    public function resultado()
    {
        return $this->belongsTo(Resultados::class, 'idResultado');
    }

    public function indicadores()
    {
        return $this->hasMany(Indicadores::class, 'idActividad');
    }

    public function eventos()
    {
        return $this->hasMany(Eventos::class, 'idActividad');
    }

    public function revisiones()
    {
        return $this->hasMany(Revisions::class, 'idActividad');
    }

    public function mediosVerificacion()
    {
        return $this->hasMany(MedioVerificacionActividad::class, 'idActividad');
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleados::class, 'empleado_actividads', 'idActividad', 'idEmpleado')
            ->withTimestamps()
            ->withPivot(['descripcion', 'created_by', 'updated_by', 'deleted_by']);
    }
}