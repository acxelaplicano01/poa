<?php

namespace App\Models\EjecucionPresupuestaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Requisicion\Requisicion;
use App\Models\EjecucionPresupuestaria\EstadoEjecucionPresupuestaria;
use App\Models\User;

class EjecucionPresupuestaria extends Model
{
    use SoftDeletes;

    protected $table = 'ejecucion_presupuestaria';

    protected $fillable = [
        'observacion',
        'fechaInicioEjecucion',
        'fechaFinEjecucion',
        'idRequisicion',
        'idEstadoEjecucion',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'fechaInicioEjecucion' => 'datetime',
        'fechaFinEjecucion' => 'datetime',
    ];

    // Relaciones
    public function requisicion(): BelongsTo
    {
        return $this->belongsTo(Requisicion::class, 'idRequisicion');
    }

    public function estadoEjecucion(): BelongsTo
    {
        return $this->belongsTo(EstadoEjecucionPresupuestaria::class, 'idEstadoEjecucion');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleEjecucionPresupuestaria::class, 'idEjecucion');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(EjecucionPresupuestariaLog::class, 'idEjecucionPresupuestaria');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}