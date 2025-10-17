<?php

namespace App\Models\Tareas;
use App\Models\BaseModel;
use App\Models\GrupoGastos\ObjetoGasto;
use App\Models\Requisicion\UnidadMedida;
use App\Models\ProcesoCompras\ProcesoCompra;
use App\Models\Cubs\Cub;

class TareaHistorico extends BaseModel
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
        return $this->belongsTo(ObjetoGasto::class, 'idobjeto');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'idunidad');
    }

    public function procesoCompra()
    {
        return $this->belongsTo(ProcesoCompra::class, 'idProcesoCompra');
    }

    public function cub()
    {
        return $this->belongsTo(Cub::class, 'idCubs');
    }
}