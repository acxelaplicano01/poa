<?php

namespace App\Models\MedioVerificacion;
use App\Models\BaseModel;
use App\Models\Tareas\SeguimientoTareas;   

class MedioVerificacion extends BaseModel
{
    protected $table = 'medio_verificacions';

    protected $fillable = [
        'nombre',
        'descripcion',
        'url',
        'nombre_Archivo',
        'idSeguimiento',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function seguimiento()
    {
        return $this->belongsTo(SeguimientoTareas::class, 'idSeguimiento');
    }
}