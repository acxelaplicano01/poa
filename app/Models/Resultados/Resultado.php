<?php

namespace App\Models\Resultados;
use App\Models\BaseModel;
use App\Models\Areas\Area;
use App\Models\Objetivos\Objetivo;
use App\Models\Dimension\Dimension;
use App\Models\Poa\Pei;
use App\Models\Poa\PeiElemento;

class Resultado extends BaseModel
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
        return $this->belongsTo(Area::class, 'idArea');
    }

    // Relación con Objetivo
    public function objetivo()
    {
        return $this->belongsTo(Objetivo::class, 'idObjetivos');
    }

    // Relación con Dimension
    public function dimension()
    {
        return $this->belongsTo(Dimension::class, 'idDimension');
    }

    // Relación con Pei
    public function pei()
    {
        return $this->belongsTo(Pei::class, 'idPei');
    }

    // Relación polimórfica con PeiElemento
    public function peiElementos()
    {
        return $this->morphMany(PeiElemento::class, 'elemento');
    }
}