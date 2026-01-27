<?php

namespace App\Models\Actas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Requisicion\Requisicion;
use App\Models\Requisicion\DetalleRequisicion;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestaria;
use App\Models\EjecucionPresupuestaria\DetalleEjecucionPresupuestaria;
use App\Models\User;

class DetalleActaEntrega extends Model
{
    use SoftDeletes;

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
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'log_cant_ejecutada' => 'decimal:2',
        'log_monto_unitario_ejecutado' => 'decimal:2',
        'log_fechaEjecucion' => 'datetime',
    ];

    public function actaEntrega(): BelongsTo
    {
        return $this->belongsTo(ActaEntrega::class, 'idActaEntrega');
    }

    public function requisicion(): BelongsTo
    {
        return $this->belongsTo(Requisicion::class, 'idRequisicion');
    }

    public function detalleRequisicion(): BelongsTo
    {
        return $this->belongsTo(DetalleRequisicion::class, 'idDetalleRequisicion');
    }

    public function ejecucionPresupuestaria(): BelongsTo
    {
        return $this->belongsTo(EjecucionPresupuestaria::class, 'idEjecucionPresupuestaria');
    }

    public function detalleEjecucionPresupuestaria(): BelongsTo
    {
        return $this->belongsTo(DetalleEjecucionPresupuestaria::class, 'idDetalleEjecucionPresupuestaria');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}