<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posgrado extends Model
{
    protected $table = 'posgrados';

    protected $fillable = [
        'nombre',
        'creditaje',
        'carga_horaria',
        'duracion_numero',
        'duracion_unidad',
        'dirigido',
        'objetivo',
        'estado',
        'convenio_id',
        'area_id',
        'tipo_id'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function convenio()
    {
        return $this->belongsTo(Convenio::class, 'convenio_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }

    public function ofertas_academicas()
    {
        return $this->hasMany(OfertasAcademica::class, 'posgrado_id');
    }
}
