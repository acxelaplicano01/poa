<?php

namespace App\Models\ProcesoCompras;

use App\Models\BaseModel;

class TipoProcesoCompra extends BaseModel
{
    protected $table = 'tipo_proceso_compra';

    protected $fillable = [
        'nombre',
        'descripcion',
        'monto_minimo',
        'monto_maximo',
        'activo',
    ];

    protected $casts = [
        'monto_minimo' => 'decimal:2',
        'monto_maximo' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Determinar el tipo de proceso según el monto
     */
    public static function obtenerPorMonto($monto)
    {
        return self::where('activo', true)
            ->where(function ($query) use ($monto) {
                $query->where(function ($q) use ($monto) {
                    $q->where('monto_minimo', '<=', $monto)
                      ->where(function ($subQuery) use ($monto) {
                          $subQuery->where('monto_maximo', '>=', $monto)
                                   ->orWhereNull('monto_maximo');
                      });
                });
            })
            ->orderBy('monto_minimo', 'desc')
            ->first();
    }

    /**
     * Scope para tipos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para ordenar por monto mínimo
     */
    public function scopeOrdenadosPorMonto($query)
    {
        return $query->orderBy('monto_minimo', 'asc');
    }
}
