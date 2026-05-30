<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    protected $table = 'fases';

    protected $fillable = [
        'n_fase',
        'nombre',
        'color'
    ];

    public function ofertas_academicas()
    {
        return $this->hasMany(OfertasAcademica::class, 'fase_id');
    }
}
