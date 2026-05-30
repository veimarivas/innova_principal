<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    protected $table = 'conceptos';

    protected $fillable = [
        'nombre',
    ];
    public function plan_concepto()
    {
        return $this->hasMany(PlanesConcepto::class, 'concepto_id');
    }
}
