<?php

namespace App\Models\Presupuestos;
use App\Models\BaseModel;
use App\Models\Tareas\Tareas;
use App\Models\GrupoGastos\Fuente;
use App\Models\Requisicion\UnidadMedidas;
use App\Models\Mes\Mes;
use App\Models\Mes\Trimestre;

class Presupuestos extends BaseModel
{
    protected $table = 'presupuestos';

    protected $fillable = [
        'cantidad',
        'costounitario',
        'total',
        'idgrupo',
        'idobjeto',
        'idtarea',
        'idfuente',
        'idunidad',
        'idMes',
        'detalle_tecnico',
        'recurso',
        'idHistorico',
        // Los campos de auditoría ya están en BaseModel
    ];

    public function tarea()
    {
        return $this->belongsTo(Tareas::class, 'idtarea');
    }

    public function fuente()
    {
        return $this->belongsTo(Fuente::class, 'idfuente');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedidas::class, 'idunidad');
    }

    public function mes()
    {
        return $this->belongsTo(Mes::class, 'idMes');
    }
}