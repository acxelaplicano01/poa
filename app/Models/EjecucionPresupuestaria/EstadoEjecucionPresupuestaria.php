<?php

namespace App\Models\EjecucionPresupuestaria;
use App\Models\BaseModel;

class EstadoEjecucionPresupuestaria extends BaseModel
{
    protected $table = 'estado_ejecucion_presupuestaria';

    protected $fillable = [
        'estado',
    ];
}
