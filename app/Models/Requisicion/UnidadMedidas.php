<?php

namespace App\Models\Requisicion;
use App\Models\BaseModel;

class UnidadMedidas extends BaseModel
{
    protected $table = 'unidadmedidas';

    protected $fillable = [
        'nombre',
        // Los campos de auditoría ya están en BaseModel
    ];
}