<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = 'sedes';

    protected $fillable = [
        'nombre'
    ];

    public function sucursales()
    {
        return $this->hasMany(Sucursale::class, 'sede_id');
    }
}
