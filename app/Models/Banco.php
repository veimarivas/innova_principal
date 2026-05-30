<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banco extends Model
{
    protected $table = 'bancos';

    protected $fillable = ['nombre', 'sigla', 'estado'];

    public function cuentas(): HasMany
    {
        return $this->hasMany(CuentaBancaria::class, 'banco_id');
    }
}