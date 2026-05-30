<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'recibo',
        'trabajadore_cargo_id',
        'monto_total',
        'descuento_bs',
        'tipo_pago',
        'fecha_pago',
        'estado',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'descuento_bs' => 'decimal:2',
        'fecha_pago' => 'date',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->recibo = IdGenerator::generate([
                'table' => $model->getTable(),
                'field' => 'recibo',
                'prefix' => 'ICV-',
                'length' => 8,
                'reset_on_year_change' => false
            ]);
        });
    }

    public function trabajadorCargo(): BelongsTo
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadore_cargo_id');
    }

    public function pagosCuotas(): HasMany
    {
        return $this->hasMany(PagosCuota::class, 'pago_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(Detalle::class, 'pago_id');
    }
}
