<?php

namespace App\Models\Actas;
use App\Models\BaseModel;
use App\Models\Actas\ActaEntrega;

class TipoActaEntrega extends BaseModel
{
    protected $table = 'tipo_acta_entrega';

    protected $fillable = [
        'tipo',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function actas()
    {
        return $this->hasMany(ActaEntrega::class, 'idTipoActaEntrega');
    }
}