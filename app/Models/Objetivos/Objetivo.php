<?php

namespace App\Models\Objetivos;
use App\Models\BaseModel;
use App\Models\Dimension\Dimension;
use App\Models\Poa\Pei;
use App\Models\Areas\Area;
use App\Models\Resultados\Resultado;
use App\Models\Poa\PeiElemento;
use Illuminate\Support\Facades\DB;

class Objetivo extends BaseModel
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'idDimension',
    ];

    public function dimension()
    {
        return $this->belongsTo(\App\Models\Dimension\Dimension::class, 'idDimension');
    }

    public function areas()
    {
        return $this->hasMany(\App\Models\Areas\Area::class, 'idObjetivo');
    }

    protected static function booted()
    {
        static::created(function ($objetivo) {
            $peiId = $objetivo->dimension?->idPei;
            if ($peiId) {
                DB::table('pei_elementos')->insert([
                    'idPei' => $peiId,  // Usa idPei como en dimensiones
                    'elemento_id' => $objetivo->id,
                    'elemento_tipo' => 'objetivos',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        static::updated(function ($objetivo) {
            $peiId = $objetivo->dimension?->idPei;
            if ($peiId) {
                DB::table('pei_elementos')->updateOrInsert(
                    [
                        'elemento_id' => $objetivo->id,
                        'elemento_tipo' => 'objetivos',
                    ],
                    [
                        'idPei' => $peiId,  // idPei
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        });

        static::deleted(function ($objetivo) {
            DB::table('pei_elementos')
                ->where('elemento_id', $objetivo->id)
                ->where('elemento_tipo', 'objetivos')
                ->delete();
        });
    }
}