<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoBanco extends Model
{
    protected $table = 'movimiento_bancos';

    protected $fillable = ['cuenta_bancaria_id', 'pago_id', 'tipo', 'monto', 'referencia', 'descripcion'];

    protected $casts = ['monto' => 'decimal:2'];

    public function cuentaBancaria(): BelongsTo
    {
        return $this->belongsTo(CuentaBancaria::class, 'cuenta_bancaria_id');
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}