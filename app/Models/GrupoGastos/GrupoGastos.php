<?php

namespace App\Models\GrupoGastos;
use App\Models\BaseModel;
use App\Models\GrupoGastos\CuentaMayores;
use App\Models\GrupoGastos\ObjetoGastos;

class GrupoGastos extends BaseModel
{
    protected $table = 'grupogastos';

    protected $fillable = [
        'nombre',
        'identificador',
    ];

    public function cuentasMayores()
    {
        return $this->hasMany(CuentaMayores::class, 'idGrupo');
    }

    public function objetoGastos()
    {
        return $this->hasMany(ObjetoGastos::class, 'idgrupo');
    }
}