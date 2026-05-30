<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';

    protected $fillable = [
        'nombre'
    ];

    public function ciudades()
    {
        return $this->hasMany(Ciudade::class, 'departamento_id');
    }
}
