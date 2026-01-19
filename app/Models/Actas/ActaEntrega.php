<?php

namespace App\Models\Actas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Requisicion\Requisicion;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestaria;
use App\Models\User;

class ActaEntrega extends Model
{
    use SoftDeletes;

    protected $table = 'acta_entrega';

    protected $fillable = [
        'correlativo',
        'fecha_extendida',
        'idTipoActaEntrega',
        'idRequisicion',
        'idEjecucionPresupuestaria',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'fecha_extendida' => 'datetime',
    ];

    public function tipoActaEntrega(): BelongsTo
    {
        return $this->belongsTo(TipoActaEntrega::class, 'idTipoActaEntrega');
    }

    public function requisicion(): BelongsTo
    {
        return $this->belongsTo(Requisicion::class, 'idRequisicion');
    }

    public function ejecucionPresupuestaria(): BelongsTo
    {
        return $this->belongsTo(EjecucionPresupuestaria::class, 'idEjecucionPresupuestaria');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleActaEntrega::class, 'idActaEntrega');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}