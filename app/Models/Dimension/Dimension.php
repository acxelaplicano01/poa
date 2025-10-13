<?php

namespace App\Models\Dimension;
use App\Models\BaseModel;
use App\Models\Poa\Pei;
use App\Models\Objetivos\Objetivo;
use App\Models\Areas\Area;
use App\Models\Resultados\Resultado;
use App\Models\Poa\PeiElemento;


class Dimension extends BaseModel
{
    protected $table = 'dimensions';

    protected $fillable = [
        'nombre',
        'descripcion',
        'idPei',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Pei
    public function pei()
    {
        return $this->belongsTo(Pei::class, 'idPei');
    }

    // Relación con Objetivos
    public function objetivos()
    {
        return $this->hasMany(Objetivo::class, 'idDimension');
    }

    // Relación con Areas
    public function areas()
    {
        return $this->hasMany(Area::class, 'idDimension');
    }

    // Relación con Resultados
    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'idDimension');
    }
    // Relación polimórfica con PeiElemento
    public function peiElementos()
    {
        return $this->morphMany(PeiElemento::class, 'elemento');
    }
}