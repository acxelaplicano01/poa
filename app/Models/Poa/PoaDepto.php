<?php

namespace App\Models\Poa;
use App\Models\BaseModel;
use App\Models\Poa\Poa;
use App\Models\Departamento\Departamento;

class PoaDepto extends BaseModel
{
    protected $table = 'poa_deptos';

    protected $fillable = [
        'isActive',
        'idPoa',
        'idDepartamento',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Poa
    public function poa()
    {
        return $this->belongsTo(Poa::class, 'idPoa');
    }

    // Relación con Departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'idDepartamento');
    }
}