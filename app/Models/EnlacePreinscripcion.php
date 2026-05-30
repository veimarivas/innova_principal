<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EnlacePreinscripcion extends Model
{
    protected $table = 'enlaces_preinscripcion';

    protected $fillable = [
        'oferta_academica_id',
        'trabajadores_cargo_id',
        'planes_pago_id',
        'token',
        'activo',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = (string) Str::uuid();
            }
        });
    }

    public function ofertaAcademica()
    {
        return $this->belongsTo(OfertasAcademica::class, 'oferta_academica_id');
    }

    public function trabajadoresCargo()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadores_cargo_id');
    }

    public function planesPago()
    {
        return $this->belongsTo(PlanesPago::class, 'planes_pago_id');
    }
}
