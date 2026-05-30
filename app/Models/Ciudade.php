<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudade extends Model
{
    protected $table = 'ciudades';

    protected $fillable = [
        'nombre',
        'departamento_id'
    ];

    public function personas()
    {
        return $this->hasMany(Persona::class, 'ciudade_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
}
