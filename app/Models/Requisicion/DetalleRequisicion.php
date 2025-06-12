<?php

namespace App\Models\Requisicion;
use App\Models\BaseModel;
use App\Models\Poa\Poa;
use App\Models\Presupuestos\Presupuesto;
use App\Models\Tareas\TareaHistorico;
use App\Models\Requisicion\Requisicion;
use App\Models\Requisicion\UnidadMedida;
use App\Models\EjecucionPresupuestaria\DetalleEjecucionPresupuestaria;

class DetalleRequisicion extends BaseModel
{
    protected $table = 'detalle_requisicion';

    protected $fillable = [
        'referenciaActaEntrega',
        'cantidad',
        'entregado',
        'idRequisicion',
        'idPoa',
        'idPresupuesto',
        'idRecurso',
        'idUnidadMedida',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function requisicion()
    {
        return $this->belongsTo(Requisicion::class, 'idRequisicion');
    }

    public function poa()
    {
        return $this->belongsTo(Poa::class, 'idPoa');
    }

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'idPresupuesto');
    }

    public function recurso()
    {
        return $this->belongsTo(TareaHistorico::class, 'idRecurso');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'idUnidadMedida');
    }

    public function ejecuciones()
    {
        return $this->hasMany(DetalleEjecucionPresupuestaria::class, 'idDetalleRequisicion');
    }
}