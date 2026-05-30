<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalGradeEntry extends Model
{
    protected $table = 'local_grade_entries';

    protected $fillable = [
        'modulo_id',
        'moodle_item_id',
        'moodle_user_id',
        'grade',
    ];

    protected $casts = [
        'grade' => 'float',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
