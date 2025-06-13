<?php

namespace App\Models\Resultados;
use App\Models\BaseModel;
use App\Models\Areas\Areas;
use App\Models\Objetivos\Objetivos;
use App\Models\Dimension\Dimensions;
use App\Models\Poa\Peis;

class Resultados extends BaseModel
{
    protected $table = 'resultados';

    protected $fillable = [
        'nombre',
        'descripcion',
        'idArea',
        'idObjetivos',
        'idDimension',
        'idPei',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Area
    public function area()
    {
        return $this->belongsTo(Areas::class, 'idArea');
    }

    // Relación con Objetivo
    public function objetivo()
    {
        return $this->belongsTo(Objetivos::class, 'idObjetivos');
    }

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
}