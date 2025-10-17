<?php

namespace App\Models\Actividad;
use App\Models\BaseModel;
use App\Models\Actividad\Actividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoActividad extends BaseModel
{
    protected $table = 'tipo_actividads';

    protected $fillable = [
        'tipo',
    ];

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'idTipoActividad');
    }
}