<?php

namespace App\Models\Actividad;
use App\Models\BaseModel;
use App\Models\Actividad\TipoActividad;
use App\Models\Poa\Poa;
use App\Models\Departamento\Departamento;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Resultados\Resultado;
use App\Models\Actividad\Indicador;
use App\Models\Actividad\Evento;
use App\Models\Actividad\Revision;
use App\Models\Actividad\MedioVerificacionActividad;
use App\Models\Empleados\Empleado;
use App\Models\Categoria\Categoria;

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

    public function resultado()
    {
        return $this->belongsTo(Resultado::class, 'idResultado');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria');
    }

    public function indicadores()
    {
        return $this->hasMany(Indicador::class, 'idActividad');
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'idActividad');
    }

    public function revisiones()
    {
        return $this->hasMany(Revision::class, 'idActividad');
    }

    public function mediosVerificacion()
    {
        return $this->hasMany(MedioVerificacionActividad::class, 'idActividad');
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_actividads', 'idActividad', 'idEmpleado')
            ->withTimestamps()
            ->withPivot(['descripcion', 'created_by', 'updated_by', 'deleted_by']);
    }
}