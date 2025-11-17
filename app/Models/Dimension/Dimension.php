<?php

namespace App\Models\Dimension;
use App\Models\BaseModel;
use App\Models\Poa\Pei;
use App\Models\Objetivos\Objetivo;
use App\Models\Areas\Area;
use App\Models\Resultados\Resultado;
use App\Models\Poa\PeiElemento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Dimension extends BaseModel
{
    use HasFactory;

    protected $table = 'dimensions';

    protected $fillable = [
        'nombre',
        'descripcion',
        'idPei',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relación con Pei
    public function pei()
    {
        return $this->belongsTo(Pei::class, 'idPei');
    }

    // Relación con Objetivos
    public function objetivos()
    {
        return $this->hasMany(Objetivo::class, 'idDimension');
    }

    // Relación con Areas
    public function areas()
    {
        return $this->hasMany(Area::class, 'idDimension');
    }

    // Relación con Resultados
    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'idDimension');
    }
    // Relación polimórfica con PeiElemento
    public function peiElementos()
    {
        return $this->morphMany(PeiElemento::class, 'elemento');
    }

    // Evento para registrar en pei_elementos al crear, actualizar o eliminar una dimensión
    protected static function booted()
    {
        static::created(function ($dimension) {
            if ($dimension->idPei) {
                DB::table('pei_elementos')->insert([
                    'idPei' => $dimension->idPei,
                    'elemento_id' => $dimension->id,
                    'elemento_tipo' => 'dimensiones',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        static::updated(function ($dimension) {
            DB::table('pei_elementos')->updateOrInsert(
                [
                    'elemento_id' => $dimension->id,
                    'elemento_tipo' => 'dimensiones',
                ],
                [
                    'idPei' => $dimension->idPei,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        });

        static::deleted(function ($dimension) {
            DB::table('pei_elementos')
                ->where('elemento_id', $dimension->id)
                ->where('elemento_tipo', 'dimensiones')
                ->delete();
        });
    }
}