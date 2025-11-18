<?php

namespace App\Models\Resultados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resultado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resultados';

    protected $fillable = [
        'nombre',
        'descripcion',
        'idArea',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function area()
    {
        return $this->belongsTo(\App\Models\Areas\Area::class, 'idArea');
    }

    public function peiElemento()
    {
        return $this->hasOne(\App\Models\Poa\PeiElemento::class, 'elemento_id')
                    ->where('elemento_tipo', 'resultados');
    }

    protected static function booted()
    {
        static::created(function ($resultado) {
            $peiId = $resultado->area?->objetivo?->dimension?->idPei;
            if ($peiId) {
                DB::table('pei_elementos')->insert([
                    'idPei' => $peiId,
                    'elemento_id' => $resultado->id,
                    'elemento_tipo' => 'resultados',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        static::updated(function ($resultado) {
            $peiId = $resultado->area?->objetivo?->dimension?->idPei;
            if ($peiId) {
                DB::table('pei_elementos')->updateOrInsert(
                    [
                        'elemento_id' => $resultado->id,
                        'elemento_tipo' => 'resultados',
                    ],
                    [
                        'idPei' => $peiId,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        });

        static::deleted(function ($resultado) {
            DB::table('pei_elementos')
                ->where('elemento_id', $resultado->id)
                ->where('elemento_tipo', 'resultados')
                ->delete();
        });
    }
}