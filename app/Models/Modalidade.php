<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidade extends Model
{
    protected $table = 'modalidades';

    protected $fillable = [
        'nombre'
    ];

    public function ofertas_academicas()
    {
        return $this->hasMany(OfertasAcademica::class, 'modalidade_id');
    }
}
