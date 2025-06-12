<?php

namespace App\Models\Actividad;
use App\Models\BaseModel;
use App\Models\Actividad\Actividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicador extends BaseModel
{
    protected $table = 'indicadores';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidadPlanificada',
        'cantidadEjecutada',
        'promedioAlcanzado',
        'isCantidad',
        'isPorcentaje',
        'idActividad',
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'idActividad');
    }
}