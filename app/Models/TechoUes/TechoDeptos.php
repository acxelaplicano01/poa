<?php

namespace App\Models\TechoUes;
use App\Models\BaseModel;
use App\Models\TechoUes\TechoUE;
use App\Models\Poa\Poas;
use App\Models\Poa\PoaDeptos;
use App\Models\Departamento\Departamentos;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Instituciones\Institucions;

class TechoDeptos extends BaseModel
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
        return $this->belongsTo(Poas::class, 'idPoa');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamentos::class, 'idDepartamento');
    }

    public function poaDepto()
    {
        return $this->belongsTo(PoaDeptos::class, 'idPoaDepto');
    }

    public function techoUE()
    {
        return $this->belongsTo(TechoUEs::class, 'idTechoUE');
    }
}