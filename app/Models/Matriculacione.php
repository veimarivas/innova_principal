<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matriculacione extends Model
{
    protected $table = 'matriculaciones';

    protected $fillable = [
        'inscripcione_id',
        'modulo_id',
        'nota_regular',
        'nota_nivelacion',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcione::class, 'inscripcione_id');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }
}
