<?php

namespace App\Models\GrupoGastos;
use App\Models\BaseModel;
use App\Models\GrupoGastos\GrupoGasto;

class CuentaMayor extends BaseModel
{
    protected $table = 'cuentas_mayors';

    protected $fillable = [
        'nombre',
        'descripcion',
        'identificador',
        'idGrupo',
    ];

    public function grupoGasto()
    {
        return $this->belongsTo(GrupoGasto::class, 'idGrupo');
    }
}