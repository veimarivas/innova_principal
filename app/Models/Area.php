<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';

    protected $fillable = [
        'nombre'
    ];

    public function posgrados()
    {
        return $this->hasMany(Posgrado::class, 'area_id');
    }
}
