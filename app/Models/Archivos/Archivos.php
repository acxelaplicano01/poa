<?php

namespace App\Models\Archivos;
use App\Models\BaseModel;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\User;

class Archivos extends BaseModel
{
    protected $table = 'archivos';

    protected $fillable = [
        'nombre_archivo',
        'url',
        'url_alternativa',
        'subido_por',
        'idUnidadEjecutora',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con usuario que subió el archivo
    public function usuario()
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    // Relación con Unidad Ejecutora
    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUnidadEjecutora');
    }
}