<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profesione extends Model
{
    protected $table = 'profesiones';

    protected $fillable = [
        'nombre'
    ];

    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'profesione_id');
    }
}
