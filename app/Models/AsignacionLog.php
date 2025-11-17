<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionLog extends Model
{
    protected $table = 'asignacion_logs';

    protected $fillable = [
        'id_asignacion',
        'id_usuario',
        'accion',
        'detalles',
    ];

    protected $casts = [
        'detalles' => 'array',
    ];

    public function asignacion()
    {
        return $this->belongsTo(AsignacionVehiculo::class, 'id_asignacion');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
