<?php

namespace App\Models\Categoria;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Categoria extends BaseModel
{
    protected $table = 'categorias';

    protected $fillable = [
        'categoria',
       
    ];
}
