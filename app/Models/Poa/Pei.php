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

    // Asegurar que los años se conviertan a enteros
    protected $casts = [
        'initialYear' => 'integer',
        'finalYear' => 'integer',
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

    // Scope para filtrar por años
    public function scopeByYearRange($query, $startYear = null, $endYear = null)
    {
        if ($startYear) {
            $query->where('initialYear', '>=', $startYear);
        }
        
        if ($endYear) {
            $query->where('finalYear', '<=', $endYear);
        }
        
        return $query;
    }

    // Accessor para obtener el período formateado
    public function getPeriodoAttribute()
    {
        return $this->initialYear . ' - ' . $this->finalYear;
    }

    // Verificar si un año está dentro del período del PEI
    public function includesYear($year)
    {
        return $year >= $this->initialYear && $year <= $this->finalYear;
    }
}