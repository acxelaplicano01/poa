<?php

namespace App\Models\Actas;
use App\Models\BaseModel;
use App\Models\Actas\TipoActaEntrega;
use App\Models\Requisicion\Requisicion;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestaria;
use App\Models\Actas\DetalleActaEntrega;

class ActaEntrega extends BaseModel
{
    protected $table = 'acta_entrega';

    protected $fillable = [
        'correlativo',
        'fecha_extendida',
        'idTipoActaEntrega',
        'idRequisicion',
        'idEjecucionPresupuestaria',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoActaEntrega::class, 'idTipoActaEntrega');
    }

    public function requisicion()
    {
        return $this->belongsTo(Requisicion::class, 'idRequisicion');
    }

    public function ejecucionPresupuestaria()
    {
        return $this->belongsTo(EjecucionPresupuestaria::class, 'idEjecucionPresupuestaria');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleActaEntrega::class, 'idActaEntrega');
    }
}