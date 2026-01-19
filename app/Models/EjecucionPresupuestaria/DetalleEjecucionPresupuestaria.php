<?php

namespace App\Models\EjecucionPresupuestaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Presupuestos\Presupuesto;
use App\Models\Requisicion\DetalleRequisicion;
use App\Models\User;

class DetalleEjecucionPresupuestaria extends Model
{
    use SoftDeletes;

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
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'cant_ejecutada' => 'decimal:2',
        'monto_unitario_ejecutado' => 'decimal:2',
        'monto_total_ejecutado' => 'decimal:2',
        'fechaEjecucion' => 'datetime',
    ];

    // Relaciones
    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class, 'idPresupuesto');
    }

    public function detalleRequisicion(): BelongsTo
    {
        return $this->belongsTo(DetalleRequisicion::class, 'idDetalleRequisicion');
    }

    public function ejecucion(): BelongsTo
    {
        return $this->belongsTo(EjecucionPresupuestaria::class, 'idEjecucion');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}