<?php

namespace App\Models\Poa;
use App\Models\BaseModel;
use App\Models\Poa\Poas;
use App\Models\Departamento\Departamentos;

class PoaDeptos extends BaseModel
{
    protected $table = 'poa_deptos';

    protected $fillable = [
        'isActive',
        'idPoa',
        'idDeptartamento',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Poa
    public function poa()
    {
        return $this->belongsTo(Poas::class, 'idPoa');
    }

    // Relación con Departamento
    public function departamento()
    {
        return $this->belongsTo(Departamentos::class, 'idDepartamento');
    }
}