<?php

namespace App\Models\TechoUes;
use App\Models\BaseModel;
use App\Models\TechoUes\TechoDepto;
use App\Models\Poa\Poa;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\GrupoGastos\Fuente;

class TechoUe extends BaseModel
{
    protected $table = 'techo_ues';

    protected $fillable = [
        'monto',
        'idUE',
        'idPoa',
        'idGrupo',
        'idFuente',
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

    public function fuente()
    {
        return $this->belongsTo(Fuente::class, 'idFuente');
    }

    public function techoDeptos()
    {
        return $this->hasMany(TechoDepto::class, 'idTechoUE');
    }

    public function grupoGasto()
    {
        return $this->belongsTo(\App\Models\GrupoGastos\GrupoGasto::class, 'idGrupo');
    }
}