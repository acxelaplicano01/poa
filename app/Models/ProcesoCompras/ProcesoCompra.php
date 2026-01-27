<?php

namespace App\Models\ProcesoCompras;
use App\Models\Empleado\Empleado;
use App\Models\BaseModel;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\ProcesoCompras\TipoProcesoCompra;

class ProcesoCompra extends BaseModel
{
    protected $table = 'procesos_compras';

    protected $fillable = [
        'nombre_proceso',
        'monto_total',
        'idTipoProcesoCompra',
        'idUE',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
    ];

    // Relación con Unidad Ejecutora
    public function unidadEjecutora()
    {
        return $this->belongsTo(UnidadEjecutora::class, 'idUE');
    }

    // Relación con Tipo de Proceso de Compra
    public function tipoProcesoCompra()
    {
        return $this->belongsTo(TipoProcesoCompra::class, 'idTipoProcesoCompra');
    }

    /**
     * Actualizar el tipo de proceso según el monto
     */
    public function actualizarTipoProceso()
    {
        if ($this->monto_total > 0) {
            $tipo = TipoProcesoCompra::obtenerPorMonto($this->monto_total);
            if ($tipo) {
                $this->idTipoProcesoCompra = $tipo->id;
                $this->save();
            }
        }
    }
}