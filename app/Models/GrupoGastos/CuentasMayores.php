<?php

namespace App\Models\GrupoGastos;
use App\Models\BaseModel;
use App\Models\GrupoGastos\GrupoGastos;

class CuentaMayores extends BaseModel
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
        return $this->belongsTo(GrupoGastos::class, 'idGrupo');
    }
}