<?php

namespace App\Models\Cubs;
use App\Models\BaseModel;
use App\Models\UnidadEjecutora\UnidadEjecutora;

class Cubs extends BaseModel
{
    protected $table = 'cubs';

    protected $fillable = [
        'IDUNSPSC',
        'descripcion_esp',
        'descripcion_regional',
        'idUE',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Unidad Ejecutora
    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUE');
    }
}