<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';

    protected $fillable = [
        'nombre'
    ];

    public function trabajadores_cargos()
    {
        return $this->hasMany(TrabajadoresCargo::class, 'cargo_id');
    }
}
