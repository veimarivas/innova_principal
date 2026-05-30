<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuotas';

    protected $fillable = [
        'inscripcione_id',
        'nombre',               // nuevo
        'n_cuota',
        'monto_bs',
        'pago_pendiente_bs',    // nuevo
        'descuento_bs',         // nuevo
        'fecha_vencimiento',
        'fecha_pago',
        'estado',
    ];

    protected $casts = [
        'monto_bs' => 'decimal:2',
        'pago_pendiente_bs' => 'decimal:2',
        'descuento_bs' => 'decimal:2',
        'fecha_vencimiento' => 'date',
        'fecha_pago' => 'date',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcione::class, 'inscripcione_id');
    }

    public function pagosCuota()
    {
        return $this->hasMany(PagosCuota::class, 'cuota_id');
    }

    public function pagoRespaldo()
    {
        return $this->belongsTo(\App\Models\PagoRespaldo::class, 'pago_respaldo_id');
    }
}
