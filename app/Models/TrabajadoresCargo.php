<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrabajadoresCargo extends Model
{
    protected $table = 'trabajadores_cargos';

    protected $fillable = [
        'sucursale_id',
        'trabajadore_id',
        'cargo_id',
        'principal',
        'estado',
        'fecha_ingreso',
        'fecha_termino',
        'nombre_cargo',
    ];

    public function getNombreCargoAttribute()
    {
        if (isset($this->attributes['nombre_cargo']) && $this->attributes['nombre_cargo']) {
            return $this->attributes['nombre_cargo'];
        }
        return $this->cargo ? $this->cargo->nombre : '';
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajadore::class, 'trabajadore_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class, 'sucursale_id');
    }

    public function ofertas_academicas_academicos()
    {
        return $this->hasMany(OfertasAcademica::class, 'responsable_academico_cargo_id');
    }

    public function ofertas_academicas_marketing()
    {
        return $this->hasMany(OfertasAcademica::class, 'responsable_marketing_cargo_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'pago_id');
    }
}
