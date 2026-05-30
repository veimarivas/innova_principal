<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuentaBancaria extends Model
{
    protected $table = 'cuentas_bancarias';

    protected $fillable = [
        'banco_id', 'numero_cuenta', 'tipo_cuenta', 
        'titular', 'ci_titular', 'imagen_qr', 'fecha_vencimiento_qr',
        'es_principal', 'estado'
    ];

    protected $casts = [
        'imagen_qr' => 'string',
        'fecha_vencimiento_qr' => 'date',
    ];

    public function banco(): BelongsTo
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoBanco::class, 'cuenta_bancaria_id');
    }
}