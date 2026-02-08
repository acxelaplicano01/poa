<?php

namespace App\Models\ProcesoCompras;

use App\Models\BaseModel;
use App\Models\Poa\Poa;

class TipoProcesoCompra extends BaseModel
{
    protected $table = 'tipo_proceso_compra';

    protected $fillable = [
        'nombre',
        'descripcion',
        'monto_minimo',
        'monto_maximo',
        'activo',
        'idPoa',
    ];

    protected $casts = [
        'monto_minimo' => 'decimal:2',
        'monto_maximo' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Relación con POA
     */
    public function poa()
    {
        return $this->belongsTo(Poa::class, 'idPoa');
    }

    /**
     * Determinar el tipo de proceso según el monto y POA
     */
    public static function obtenerPorMonto($monto, $idPoa = null)
    {
        $query = self::where('activo', true)
            ->where(function ($query) use ($monto) {
                $query->where(function ($q) use ($monto) {
                    $q->where('monto_minimo', '<=', $monto)
                      ->where(function ($subQuery) use ($monto) {
                          $subQuery->where('monto_maximo', '>=', $monto)
                                   ->orWhereNull('monto_maximo');
                      });
                });
            });
        
        if ($idPoa) {
            $query->where('idPoa', $idPoa);
        }
        
        return $query->orderBy('monto_minimo', 'desc')->first();
    }

    /**
     * Scope para tipos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
    
    /**
     * Scope para filtrar por POA
     */
    public function scopePorPoa($query, $idPoa)
    {
        return $query->where('idPoa', $idPoa);
    }

    /**
     * Scope para ordenar por monto mínimo
     */
    public function scopeOrdenadosPorMonto($query)
    {
        return $query->orderBy('monto_minimo', 'asc');
    }
}
