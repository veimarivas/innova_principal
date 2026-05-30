<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodleMatricula extends Model
{
    protected $table = 'moodle_matriculas';

    protected $fillable = [
        'inscripcion_id',
        'docente_id',
        'modulo_id',
        'moodle_user_id',
        'moodle_course_id',
        'matriculado_at',
        'acceso_suspendido',
    ];

    protected $casts = [
        'matriculado_at'    => 'datetime',
        'acceso_suspendido' => 'boolean',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }
}
