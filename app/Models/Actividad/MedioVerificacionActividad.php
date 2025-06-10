<?php

namespace App\Models\Actividad;
use App\Models\BaseModel;
use App\Models\Actividad\Actividad;
use App\Models\Archivos\Archivos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedioVerificacionActividad extends BaseModel
{
    protected $table = 'medio_verificacion_actividad';

    protected $fillable = [
        'observacion',
        'idArchivo',
        'idActividad',
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }

    public function archivo()
    {
        return $this->belongsTo(Archivos::class, 'idArchivo');
    }
}