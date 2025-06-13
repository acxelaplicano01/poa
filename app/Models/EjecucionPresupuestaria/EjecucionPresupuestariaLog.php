<?php

namespace App\Models\EjecucionPresupuestaria;
use App\Models\BaseModel;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestaria;

class EjecucionPresupuestariaLogs extends BaseModel
{
    protected $table = 'ejecucion_presupuestaria_logs';

    protected $fillable = [
        'observacion',
        'log',
        'idEjecucionPresupuestaria',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function ejecucionPresupuestaria()
    {
        return $this->belongsTo(EjecucionPresupuestaria::class, 'idEjecucionPresupuestaria');
    }
}