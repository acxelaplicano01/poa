<?php

namespace App\Models\UnidadEjecutora;
use App\Models\BaseModel;
use App\Models\Instituciones\Institucions;
use App\Models\Departamento\Departamentos;

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
        return $this->belongsTo(Institucions::class, 'idInstitucion');
    }

    // Relación con Departamentos
    public function departamentos()
    {
        return $this->hasMany(Departamentos::class, 'idUnidadEjecutora');
    }
}