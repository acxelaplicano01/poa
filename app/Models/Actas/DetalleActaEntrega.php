<?php

namespace App\Models\Actas; 
use App\Models\BaseModel;
use App\Models\Requisicion\Requisicion;
use App\Models\Requisicion\DetalleRequisicion;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestaria;
use App\Models\EjecucionPresupuestaria\DetalleEjecucionPresupuestaria;

class DetalleActaEntrega extends BaseModel
{
    protected $table = 'detalle_acta_entrega';

    protected $fillable = [
        'log_cant_ejecutada',
        'log_monto_unitario_ejecutado',
        'log_fechaEjecucion',
        'idActaEntrega',
        'idRequisicion',
        'idDetalleRequisicion',
        'idEjecucionPresupuestaria',
        'idDetalleEjecucionPresupuestaria',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function actaEntrega()
    {
        return $this->belongsTo(ActaEntrega::class, 'idActaEntrega');
    }

    public function requisicion()
    {
        return $this->belongsTo(Requisicion::class, 'idRequisicion');
    }

    public function detalleRequisicion()
    {
        return $this->belongsTo(DetalleRequisicion::class, 'idDetalleRequisicion');
    }

    public function ejecucionPresupuestaria()
    {
        return $this->belongsTo(EjecucionPresupuestaria::class, 'idEjecucionPresupuestaria');
    }

    public function detalleEjecucionPresupuestaria()
    {
        return $this->belongsTo(DetalleEjecucionPresupuestaria::class, 'idDetalleEjecucionPresupuestaria');
    }
}