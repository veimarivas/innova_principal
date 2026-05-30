<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'color',
        'modulo_id',
        'trabajadores_cargo_id',
        'reprogramado_id',
        'enlace_videollamada_id',
        'enlace_grabacion',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function getColorAttribute($value)
    {
        return $value;
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function trabajadorCargo()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadores_cargo_id');
    }

    public function reprogramados()
    {
        return $this->hasMany(Horario::class, 'reprogramado_id');
    }

    public function reprogramado()
    {
        return $this->belongsTo(Horario::class, 'reprogramado_id');
    }

    public function reprogramado_a()
    {
        return $this->hasOne(Horario::class, 'reprogramado_id');
    }

    public function enlaceVideollamada()
    {
        return $this->belongsTo(EnlaceVideollamada::class, 'enlace_videollamada_id');
    }
}
