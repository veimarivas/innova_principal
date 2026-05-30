<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $fillable = [
        'trabajadore_cargo_id', 'nombre', 'monto_inicial', 
        'monto_actual', 'estado', 'fecha_apertura', 'fecha_cierre'
    ];

    protected $casts = [
        'monto_inicial' => 'decimal:2',
        'monto_actual' => 'decimal:2',
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
    ];

    public function trabajadorCargo(): BelongsTo
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadore_cargo_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(CajaMovimiento::class, 'caja_id');
    }
}