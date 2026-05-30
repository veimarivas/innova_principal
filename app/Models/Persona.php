<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

    protected $fillable = [
        'carnet',
        'nombres',
        'expedido',
        'apellido_paterno',
        'apellido_materno',
        'sexo',
        'estado_civil',
        'fecha_nacimiento',
        'correo',
        'direccion',
        'celular',
        'telefono',
        'ciudade_id',
        'fotografia',
        'fotografia_carnet',
        'carnet_verificado',
        'fotografia_certificado_nacimiento',
        'certificado_nacimiento_verificado'
    ];

    public function usuario()
    {
        return $this->hasOne(User::class, 'persona_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudade::class, 'ciudade_id');
    }

    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'persona_id');
    }

    public function trabajador()
    {
        return $this->hasOne(Trabajadore::class, 'persona_id');
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'persona_id');
    }

    public function docente()
    {
        return $this->hasOne(Docente::class, 'persona_id');
    }
}
