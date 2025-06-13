<?php

namespace App\Models\Instituciones;
use App\Models\BaseModel;
use App\Models\Poa\Pei;
use Illuminate\Database\Eloquent\Model;

class Institucion extends BaseModel
{
    protected $table = 'institucions';

    protected $fillable = [
        'nombre',
        'descripcion',
        // Los campos de auditoría ya están en BaseModel
    ];

    // Relaciones 
    public function peis()
    {
        return $this->hasMany(Pei::class, 'idInstitucion');
    }
}