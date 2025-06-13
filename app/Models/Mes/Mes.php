<?php

namespace App\Models\Mes;
use App\Models\BaseModel;
use App\Models\Mes\Trimestre;

class Mes extends BaseModel
{
    protected $table = 'mes';

    protected $fillable = [
        'mes',
        'idTrimestre',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'idTrimestre');
    }
}