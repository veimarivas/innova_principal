<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagosCuota extends Model
{
    protected $table = 'pagos_cuotas';

    protected $fillable = [
        'cuota_id',
        'pago_id',
        'monto_bs',
        'fecha_pago',
    ];

    protected $casts = [
        'monto_bs' => 'decimal:2',
        'fecha_pago' => 'date',
    ];

    public function cuota(): BelongsTo
    {
        return $this->belongsTo(Cuota::class, 'cuota_id');
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}
