<?php

namespace App\Models\ProcesoCompras;
use App\Models\BaseModel;
use App\Models\UnidadEjecutora\UnidadEjecutora;

class ProcesoCompra extends BaseModel
{
    protected $table = 'procesos_compras';

    protected $fillable = [
        'nombre_proceso',
        'idUE',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Unidad Ejecutora
    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUE');
    }
}