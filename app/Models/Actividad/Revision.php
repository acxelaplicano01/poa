<?php

namespace App\Models\Actividad;
use App\Models\BaseModel;
use App\Models\Actividad\Actividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends BaseModel
{
    protected $table = 'revisions';

    protected $fillable = [
        'revision',
        'tipo',
        'corregido',
        'idActividad',
        'idElemento',
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }
}