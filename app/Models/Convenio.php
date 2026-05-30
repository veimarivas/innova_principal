<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convenio extends Model
{
    protected $table = 'convenios';

    protected $fillable = [
        'nombre',
        'imagen',
        'sigla'
    ];

    public function posgrados()
    {
        return $this->hasMany(Posgrado::class, 'convenio_id');
    }
}
