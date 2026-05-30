<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnlaceVideollamada extends Model
{
    protected $table = 'enlaces_videollamada';

    protected $fillable = [
        'cuenta_id',
        'nombre',
        'enlace',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function cuenta()
    {
        return $this->belongsTo(CuentasVideollamada::class, 'cuenta_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'enlace_videollamada_id');
    }
}
