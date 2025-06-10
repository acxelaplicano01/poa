<?php

namespace App\Models\Mes;
use App\Models\BaseModel;
use App\Models\Mes\Mes;
use App\Models\Mes\Trimestre;

class Trimestre extends BaseModel
{
    protected $table = 'trimestre';

    protected $fillable = [
        'trimestre',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function meses()
    {
        return $this->hasMany(Mes::class, 'idTrimestre');
    }
}