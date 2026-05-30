<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanesPago extends Model
{
    protected $table = 'planes_pagos';

    protected $fillable = [
        'nombre',
        'habilitado',
        'principal',
        'es_promocion',
        'fecha_inicio_promocion',
        'fecha_fin_promocion'
    ];

    protected $casts = [
        'habilitado' => 'boolean',
        'es_promocion' => 'boolean',
        'principal' => 'boolean',
        'fecha_inicio_promocion' => 'date',
        'fecha_fin_promocion' => 'date'
    ];

    public function plan_concepto()
    {
        return $this->hasMany(PlanesConcepto::class, 'planes_pago_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcione::class, 'planes_pago_id');
    }
}
