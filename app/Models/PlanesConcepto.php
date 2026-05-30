<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanesConcepto extends Model
{
    protected $table = 'planes_conceptos';

    protected $fillable = [
        'n_cuotas',
        'pago_bs',
        'ofertas_academica_id',
        'planes_pago_id',
        'concepto_id',
        'precio_regular',
        'descuento_bs'
    ];

    protected $casts = [
        'precio_regular' => 'decimal:2',
        'descuento_bs' => 'decimal:2',
        'pago_bs' => 'decimal:2'
    ];

    public function oferta_academica()
    {
        return $this->belongsTo(OfertasAcademica::class, 'ofertas_academica_id');
    }

    public function plan_pago()
    {
        return $this->belongsTo(PlanesPago::class, 'planes_pago_id');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'concepto_id');
    }
}
