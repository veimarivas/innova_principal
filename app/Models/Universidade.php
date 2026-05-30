<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Universidade extends Model
{
    protected $table = 'universidades';

    protected $fillable = [
        'nombre',
        'sigla'
    ];

    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'universidade_id');
    }
}
