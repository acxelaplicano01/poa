<?php

namespace App\Models\TechoUes;
use App\Models\BaseModel;
use App\Models\TechoUes\TechoUe;
use App\Models\Poa\Poa;
use App\Models\Poa\PoaDepto;
use App\Models\Departamento\Departamento;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Instituciones\Institucions;

class TechoDepto extends BaseModel
{
    protected $table = 'techo_deptos';

    protected $fillable = [
        'monto',
        'idUE',
        'idPoa',
        'idDepartamento',
        'idPoaDepto',
        'idTechoUE',
        'idGrupo',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUE');
    }

    public function poa()
    {
        return $this->belongsTo(Poa::class, 'idPoa');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'idDepartamento');
    }

    public function poaDepto()
    {
        return $this->belongsTo(PoaDepto::class, foreignKey: 'idPoaDepto');
    }

    public function techoUE()
    {
        return $this->belongsTo(TechoUe::class, 'idTechoUE');
    }

    public function grupoGasto()
    {
        return $this->belongsTo(\App\Models\GrupoGastos\GrupoGasto::class, 'idGrupo');
    }
}