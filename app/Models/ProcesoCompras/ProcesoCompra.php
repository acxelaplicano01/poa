<?php

namespace App\Models\ProcesoCompras;
use App\Models\Empleado\Empleado;
use App\Models\BaseModel;
use App\Models\UnidadEjecutora\UnidadEjecutora;

class ProcesoCompra extends BaseModel
{
    protected $table = 'procesos_compras';

    protected $fillable = [
        'nombre_proceso',
        'idUE',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relación con Unidad Ejecutora
    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUE');
    }

}