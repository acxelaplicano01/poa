<?php

namespace App\Models\Mes;
use App\Models\BaseModel;
use App\Models\Mes\Mes;

class Trimestre extends BaseModel
{
    protected $table = 'trimestres';

    protected $fillable = [
        'trimestre',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function meses()
    {
        return $this->hasMany(Mes::class, 'idTrimestre');
    }
}