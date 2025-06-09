<?php

namespace App\Models;

class ActaEntrega extends BaseModel
{
    protected $table = 'acta_entregas';

    protected $fillable = [
        'correlativo',
        'fecha_extendida',
    ];

    protected $casts = [
        'fecha_extendida' => 'datetime',
    ];

    protected $dates = ['fecha_extendida'];
}
