<?php

namespace App\Models\GrupoGastos;
use App\Models\BaseModel;

class Fuente extends BaseModel
{
    protected $table = 'fuente';

    protected $fillable = [
        'nombre',
        'identificador',
    ];
}