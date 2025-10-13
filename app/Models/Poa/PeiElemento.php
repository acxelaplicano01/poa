<?php

namespace App\Models\Poa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Poa\Pei;

class PeiElemento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pei_elementos';

    protected $fillable = [
        'pei_id',
        'elemento_id',
        'elemento_tipo',
    ];

    /**
     * Get the parent elemento model (objetivo, resultado, area, dimension).
     */
    public function elemento()
    {
        return $this->morphTo();
    }

    /**
     * Get the PEI associated with this elemento.
     */
    public function pei()
    {
        return $this->belongsTo(Pei::class);
    }

    /**
     * Scope to filter by elemento_tipo.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('elemento_tipo', $type);
    }

}