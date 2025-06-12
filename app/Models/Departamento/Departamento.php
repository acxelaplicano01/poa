<?php

namespace App\Models\Departamento;
use App\Models\BaseModel;
use App\Models\UnidadEjecutora\UnidadEjecutora;

class Departamento extends BaseModel
{
    protected $table = 'departamentos';

    protected $fillable = [
        'name',
        'siglas',
        'estructura',
        'tipo',
        'idUnidadEjecutora',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con UnidadEjecutora
    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUnidadEjecutora');
    }
}