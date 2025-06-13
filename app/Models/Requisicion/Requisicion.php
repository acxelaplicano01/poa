<?php

namespace App\Models\Requisicion;
use App\Models\BaseModel;
use App\Models\Poa\Poa;
use App\Models\Departamento\Departamento;
use App\Models\Requisicion\EstadoRequisicion;
use App\Models\RequisicionLog\EstadoRequisicionLog;
use App\Models\User;

class Requisicion extends BaseModel
{
    protected $table = 'requisicion';

    protected $fillable = [
        'correlativo',
        'descripcion',
        'observacion',
        'createdBy',
        'approvedBy',
        'idPoa',
        'idDepartamento',
        'idEstado',
        'fechaSolicitud',
        'fechaRequerido',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function poa()
    {
        return $this->belongsTo(Poa::class, 'idPoa');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'idDepartamento');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoRequisicion::class, 'idEstado');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'approvedBy');
    }

    public function logs()
    {
        return $this->hasMany(EstadoRequisicionLog::class, 'idRequisicion');
    }
}