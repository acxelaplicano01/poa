<?php

namespace App\Models\EjecucionPresupuestaria;
use App\Models\BaseModel;
use App\Models\EjecucionPresupuestaria\EstadoEjecucionPresupuestaria;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestariaLog;
use App\Models\Requisicion\Requisicion;

class EjecucionPresupuestaria extends BaseModel
{
    protected $table = 'ejecucion_presupuestaria';

    protected $fillable = [
        'observacion',
        'fechaInicioEjecucion',
        'fechaFinEjecucion',
        'idRequisicion',
        'idEstadoEjecucion',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function requisicion()
    {
        return $this->belongsTo(Requisicion::class, 'idRequisicion');
    }

    public function estadoEjecucion()
    {
        return $this->belongsTo(EstadoEjecucionPresupuestaria::class, 'idEstadoEjecucion');
    }

    public function logs()
    {
        return $this->hasMany(EjecucionPresupuestariaLogs::class, 'idEjecucionPresupuestaria');
    }
}