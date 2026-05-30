<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    protected $table = 'estudios';

    protected $fillable = [
        'grados_academico_id',
        'principal',
        'profesione_id',
        'persona_id',
        'universidade_id',
        'estado',
        'documento_academico',
        'documento_provision_nacional',
        'documento_academico_verificado',
        'documento_provision_verificado',
    ];

    public function grado_academico()
    {
        return $this->belongsTo(GradoAcademico::class, 'grados_academico_id');
    }

    public function profesion()
    {
        return $this->belongsTo(Profesione::class, 'profesione_id');
    }

    public function universidad()
    {
        return $this->belongsTo(Universidade::class, 'universidade_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}
