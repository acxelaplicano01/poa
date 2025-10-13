<?php

namespace App\Models\Areas;
use App\Models\BaseModel;
use App\Models\Objetivos\Objetivo;
use App\Models\Dimension\Dimension;
use App\Models\Resultados\Resultado;
use App\Models\Poa\Pei;
use App\Models\Poa\PeiElemento;

class Area extends BaseModel
{
    protected $table = 'areas';

    protected $fillable = [
        'nombre',
        'idObjetivos',
        'idDimension',
        'idPei',
        // Los campos de auditoría ya están en BaseModel
    ];

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

    // Relación con Resultados
    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'idArea');
    }

    // Relación polimórfica con PeiElemento
    public function peiElementos()
    {
        return $this->morphMany(PeiElemento::class, 'elemento');
    }
}