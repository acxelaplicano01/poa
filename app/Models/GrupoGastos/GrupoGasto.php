<?php

namespace App\Models\GrupoGastos;
use App\Models\BaseModel;
use App\Models\GrupoGastos\CuentaMayor;
use App\Models\GrupoGastos\ObjetoGasto;

class GrupoGasto extends BaseModel
{
    protected $table = 'grupogastos';

    protected $fillable = [
        'nombre',
        'identificador',
    ];

    public function cuentasMayores()
    {
        return $this->hasMany(CuentaMayor::class, 'idGrupo');
    }

    public function objetoGastos()
    {
        return $this->hasMany(ObjetoGasto::class, 'idgrupo');
    }
}