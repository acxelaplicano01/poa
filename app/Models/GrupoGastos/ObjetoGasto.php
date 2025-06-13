<?php

namespace App\Models\GrupoGastos;
use App\Models\BaseModel;
use App\Models\GrupoGastos\GrupoGasto;
use App\Models\GrupoGastos\CuentaMayor;

class ObjetoGasto extends BaseModel
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
        return $this->belongsTo(GrupoGasto::class, 'idgrupo');
    }
}