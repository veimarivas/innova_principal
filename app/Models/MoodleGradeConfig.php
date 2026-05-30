<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodleGradeConfig extends Model
{
    protected $table = 'moodle_grade_configs';

    protected $fillable = [
        'modulo_id',
        'moodle_item_id',
        'activity_name',
        'activity_type',
        'max_grade',
        'cmid',
        'weight',
        'is_cumulative',
        'calculation_mode',
    ];

    protected $casts = [
        'weight'        => 'float',
        'max_grade'     => 'float',
        'is_cumulative' => 'boolean',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
