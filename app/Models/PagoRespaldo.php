<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoRespaldo extends Model
{
    protected $table = 'pagos_respaldos';

    protected $fillable = [
        'inscripcione_id',
        'cuota_id',
        'archivo',
        'observaciones',
        'estado',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcione::class, 'inscripcione_id');
    }

    public function cuotas()
    {
        return $this->belongsToMany(Cuota::class, 'pago_respaldo_cuota', 'pago_respaldo_id', 'cuota_id')
                    ->withTimestamps();
    }
}
