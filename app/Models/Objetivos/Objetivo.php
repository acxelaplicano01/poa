<?php

namespace App\Models\Objetivos;
use App\Models\BaseModel;
use App\Models\Dimension\Dimension;
use App\Models\Poa\Pei;
use App\Models\Areas\Area;
use App\Models\Resultados\Resultado;
use App\Models\Poa\PeiElemento;

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
        return $this->belongsTo(Dimension::class, 'idDimension');
    }

    // Relación con Pei
    public function pei()
    {
        return $this->belongsTo(Pei::class, 'idPei');
    }

    // Relación con Areas
    public function areas()
    {
        return $this->hasMany(Area::class, 'idObjetivos');
    }

    // Relación con Resultados
    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'idObjetivos');
    }

    // Relación polimórfica con PeiElemento
    public function peiElementos()
    {
        return $this->morphMany(PeiElemento::class, 'elemento');
    }
}