<?php

namespace App\Models\UnidadEjecutora;
use App\Models\BaseModel;
use App\Models\Instituciones\Institucion;
use App\Models\Departamento\Departamento;

class UnidadEjecutora extends BaseModel
{
    protected $table = 'unidad_ejecutora';

    protected $fillable = [
        'name',
        'descripcion',
        'estructura',
        'idInstitucion',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Institucion
    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'idInstitucion');
    }

    // Relación con Departamentos
    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'idUnidadEjecutora');
    }
}