<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programas';

    protected $fillable = [
        'nombre',
        'moodle_category_id',
    ];

    public function ofertas_academicas()
    {
        return $this->hasMany(OfertasAcademica::class, 'programa_id');
    }
}
