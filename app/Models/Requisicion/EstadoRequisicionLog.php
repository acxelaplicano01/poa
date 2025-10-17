<?php

namespace App\Models\Requisicion;
use App\Models\BaseModel;
use App\Models\Requisicion\Requisicion;

class EstadoRequisicionLog extends BaseModel
{
    protected $table = 'estado_requisicion_logs';

    protected $fillable = [
        'observacion',
        'log',
        'idRequisicion',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function requisicion()
    {
        return $this->belongsTo(Requisicion::class, 'idRequisicion');
    }
}