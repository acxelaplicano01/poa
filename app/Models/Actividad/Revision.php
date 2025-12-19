<?php

namespace App\Models\Actividad;
use App\Models\BaseModel;
use App\Models\Actividad\Actividad;
use App\Models\User;
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

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}