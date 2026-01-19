<?php

namespace App\Models\Ejecucionpresupuestaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\EjecucionPresupuestaria\EjecucionPresupuestaria;

class EstadoEjecucionPresupuestaria extends Model
{
    use SoftDeletes;

    protected $table = 'estado_ejecucion_presupuestaria';

    protected $fillable = [
        'estado',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function ejecuciones(): HasMany
    {
        return $this->hasMany(EjecucionPresupuestaria::class, 'idEstadoEjecucion');
    }
}
