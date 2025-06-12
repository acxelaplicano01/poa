<?php

namespace App\Models\Actividad;
use App\Models\BaseModel;
use App\Models\Actividad\Actividad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends BaseModel
{
    protected $table = 'eventos';

    protected $fillable = [
        'evento',
        'tipo',
        'fecha',
        'idUser',
        'idActividad',
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUser');
    }
}