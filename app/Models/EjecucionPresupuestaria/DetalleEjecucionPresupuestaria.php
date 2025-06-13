<?php

namespace App\Models\EjecucionPresupuestaria;
use App\Models\BaseModel;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestaria;
use App\Models\Presupuestos\Presupuestos;
use App\Models\Requisicion\DetalleRequisicion;
use App\Models\Requisicion\Requisicion;

class DetalleEjecucionPresupuestaria extends BaseModel
{
    protected $table = 'detalle_ejecucion_presupuestaria';

    protected $fillable = [
        'observacion',
        'referenciaActaEntrega',
        'cant_ejecutada',
        'monto_unitario_ejecutado',
        'monto_total_ejecutado',
        'fechaEjecucion',
        'idPresupuesto',
        'idDetalleRequisicion',
        'idEjecucion',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuestos::class, 'idPresupuesto');
    }

    public function detalleRequisicion()
    {
        return $this->belongsTo(DetalleRequisicion::class, 'idDetalleRequisicion');
    }

    public function ejecucionPresupuestaria()
    {
        return $this->belongsTo(EjecucionPresupuestaria::class, 'idEjecucion');
    }
}