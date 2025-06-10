<?php

namespace App\Models\Tareas;
use App\Models\BaseModel;
use App\Models\GrupoGastos\ObjetoGastos;
use App\Models\Requisicion\UnidadMedidas;
use App\Models\ProcesoCompras\ProcesosCompras;
use App\Models\Cubs\Cubs;

class TareaHistoricos extends BaseModel
{
    protected $table = 'tareas_historicos';

    protected $fillable = [
        'nombre',
        'idobjeto',
        'idunidad',
        'idProcesoCompra',
        'idCubs',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relaciones (opcional)
    public function objeto()
    {
        return $this->belongsTo(ObjetoGastos::class, 'idobjeto');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedidas::class, 'idunidad');
    }

    public function procesoCompra()
    {
        return $this->belongsTo(ProcesosCompras::class, 'idProcesoCompra');
    }

    public function cub()
    {
        return $this->belongsTo(Cubs::class, 'idCubs');
    }
}