<?php

namespace App\Models\Requisicion;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class EstadoRequisicion extends BaseModel
{
    protected $table = 'estado_requisicion';

    protected $fillable = [
        'estado',
        // Los campos de auditoría ya están en BaseModel
    ];
}