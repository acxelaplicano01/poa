<?php

namespace App\Models\Actas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class TipoActaEntrega extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_acta_entrega';

    protected $fillable = [
        'tipo',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}