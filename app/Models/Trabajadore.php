<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajadore extends Model
{
    protected $table = 'trabajadores';

    protected $fillable = [
        'persona_id'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function trabajadores_cargos()
    {
        return $this->hasMany(TrabajadoresCargo::class, 'trabajadore_id');
    }
}
