<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detalle extends Model
{
    protected $table = 'detalles';

    protected $fillable = [
        'pago_id', 'tipo_pago', 'monto_bs', 
        'cuenta_bancaria_id', 'caja_id', 'referencia'
    ];

    protected $casts = [
        'monto_bs' => 'decimal:2'
    ];

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }

    public function cuentaBancaria(): BelongsTo
    {
        return $this->belongsTo(CuentaBancaria::class, 'cuenta_bancaria_id');
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }
}
