<?php

namespace App\Models\Dimension;
use App\Models\BaseModel;
use App\Models\Poa\Peis;
use App\Models\Objetivos\Objetivos;
use App\Models\Area\Area;

class Dimensions extends BaseModel
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
        return $this->belongsTo(Peis::class, 'idPei');
    }

    // Relación con Objetivos
    public function objetivos()
    {
        return $this->hasMany(Objetivos::class, 'idDimension');
    }

    // Relación con Areas
    public function areas()
    {
        return $this->hasMany(Areas::class, 'idDimension');
    }

    // Relación con Resultados
    public function resultados()
    {
        return $this->hasMany(Resultados::class, 'idDimension');
    }
}