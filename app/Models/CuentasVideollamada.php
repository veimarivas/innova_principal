<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentasVideollamada extends Model
{
    protected $table = 'cuentas_videollamada';

    protected $fillable = [
        'nombre',
        'plataforma',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function enlaces()
    {
        return $this->hasMany(EnlaceVideollamada::class, 'cuenta_id');
    }
}
