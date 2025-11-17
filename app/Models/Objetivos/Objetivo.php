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
    protected $table = 'objetivos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'idDimension',
        'idPei',
        // Los campos de auditoría ya están en BaseModel
    ];

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

    // Relación con Areas
    public function areas()
    {
        return $this->hasMany(Area::class, 'idObjetivos');
    }

    // Relación con Resultados
    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'idObjetivos');
    }

    // Relación polimórfica con PeiElemento
    public function peiElementos()
    {
        return $this->morphMany(PeiElemento::class, 'elemento');
    }

    protected static function booted()
    {
        static::creating(function ($objetivo) {
            if (!$objetivo->idPei && $objetivo->idDimension) {
                $objetivo->idPei = Dimension::whereKey($objetivo->idDimension)->value('idPei');
            }
        });

        static::updating(function ($objetivo) {
            if (!$objetivo->idPei && $objetivo->idDimension) {
                $objetivo->idPei = Dimension::whereKey($objetivo->idDimension)->value('idPei');
            }
        });

        static::created(function ($objetivo) {
            $peiId = $objetivo->idPei ?? Dimension::whereKey($objetivo->idDimension)->value('idPei');

            if ($peiId) {
                DB::table('pei_elementos')->insert([
                    'idPei' => $peiId,
                    'elemento_id' => $objetivo->id,
                    'elemento_tipo' => 'objetivos',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        static::updated(function ($objetivo) {
            $peiId = $objetivo->idPei ?? Dimension::whereKey($objetivo->idDimension)->value('idPei');

            if ($peiId) {
                DB::table('pei_elementos')->updateOrInsert(
                    [
                        'elemento_id' => $objetivo->id,
                        'elemento_tipo' => 'objetivos',
                    ],
                    [
                        'idPei' => $peiId,
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