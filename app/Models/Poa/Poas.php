<?php

namespace App\Models\Poa;
use App\Models\BaseModel;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Instituciones\Institucions;
use App\Models\Poa\PoaDeptos;


class Poas extends BaseModel
{
    protected $table = 'poas';

    protected $fillable = [
        'name',
        'anio',
        'idInstitucion',
        'idUE',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Institucion
    public function institucion()
    {
        return $this->belongsTo(Institucions::class, 'idInstitucion');
    }

    // Relación con Unidad Ejecutora
    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUE');
    }

    // Relación con PoaDeptos
    public function poaDeptos()
    {
        return $this->hasMany(PoaDeptos::class, 'idPoaUE');
    }
}