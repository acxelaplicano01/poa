<?php

namespace App\Models\Poa;
use App\Models\BaseModel;
use App\Models\Dimension\Dimension;
use App\Models\Instituciones\Institucion;

class Pei extends BaseModel
{
    protected $table = 'peis';

    protected $fillable = [
        'name',
        'initialYear',
        'finalYear',
        'idInstitucion',   
    ];

    // Relación con Institucion
    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'idInstitucion');
    }

    // Relación con Dimensions
    public function dimensions()
    {
        return $this->hasMany(Dimension::class, 'idPei');
    }
}