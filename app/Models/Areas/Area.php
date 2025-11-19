<?php

namespace App\Models\Areas;
use App\Models\BaseModel;
use App\Models\Objetivos\Objetivo;
use App\Models\Dimension\Dimension;
use App\Models\Resultados\Resultado;
use App\Models\Poa\Pei;

class Area extends BaseModel
{
    protected $table = 'areas';

    protected $fillable = [
        'nombre',
        'idObjetivo', // Corregido para consistencia
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Objetivo
    public function objetivo()
    {
        return $this->belongsTo(Objetivo::class, 'idObjetivo');
    }

    // Relación con Dimension
    public function dimension()
    {
        return $this->belongsTo(Dimension::class, 'idDimension');
    }

    // Relación con Pei
    public function pei()
    {
        return $this->belongsTo(Pei::class, 'idPei');
    }

    // Relación con Resultados
    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'idArea');
    }

    protected static function booted()
    {
        static::created(function ($area) {
            // Obtener el objetivo relacionado para determinar el PEI
            $objetivo = $area->objetivo;

            if ($objetivo) {
                \DB::table('pei_elementos')->insert([
                    'idPei' => $objetivo->idPei, // Relación con el PEI a través del objetivo
                    'elemento_id' => $area->id,
                    'elemento_tipo' => 'areas', // Tipo de elemento
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        static::updated(function ($area) {
            // Obtener el objetivo relacionado para determinar el PEI
            $objetivo = $area->objetivo;

            if ($objetivo) {
                \DB::table('pei_elementos')->updateOrInsert(
                    [
                        'elemento_id' => $area->id,
                        'elemento_tipo' => 'areas',
                    ],
                    [
                        'idPei' => $objetivo->idPei, // Relación con el PEI a través del objetivo
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        });

        static::deleted(function ($area) {
            // Eliminar el registro correspondiente en pei_elementos
            \DB::table('pei_elementos')
                ->where('elemento_id', $area->id)
                ->where('elemento_tipo', 'areas')
                ->delete();
        });
    }
}