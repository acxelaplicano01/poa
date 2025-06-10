<?php

namespace App\Models\Objetivos;
use App\Models\BaseModel;
use App\Models\Dimension\Dimension;
use App\Models\Poa\Peis;
use App\Models\Areas\Areas;
use App\Models\Resultados\Resultados;

class Objetivo extends BaseModel
{
    protected $table = 'objetivos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'idDimension',
        'idPei',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Dimension
    public function dimension()
    {
        return $this->belongsTo(Dimensions::class, 'idDimension');
    }

    // Relación con Pei
    public function pei()
    {
        return $this->belongsTo(Peis::class, 'idPei');
    }

    // Relación con Areas
    public function areas()
    {
        return $this->hasMany(Areas::class, 'idObjetivos');
    }

    // Relación con Resultados
    public function resultados()
    {
        return $this->hasMany(Resultados::class, 'idObjetivos');
    }
}