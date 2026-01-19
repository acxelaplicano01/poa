<?php

namespace App\Models\EjecucionPresupuestaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class EjecucionPresupuestariaLog extends Model
{
    use SoftDeletes;

    protected $table = 'ejecucion_presupuestaria_logs';

    protected $fillable = [
        'observacion',
        'log',
        'idEjecucionPresupuestaria',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function ejecucion(): BelongsTo
    {
        return $this->belongsTo(EjecucionPresupuestaria::class, 'idEjecucionPresupuestaria');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}