<?php

namespace App\Models\GrupoGastos;
use App\Models\BaseModel;
use App\Models\GrupoGastos\GrupoGasto;
use App\Models\GrupoGastos\CuentaMayores;

class ObjetoGastos extends BaseModel
{
    protected $table = 'objetogastos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'identificador',
        'idgrupo',
    ];

    public function grupoGasto()
    {
        return $this->belongsTo(GrupoGastos::class, 'idgrupo');
    }
}