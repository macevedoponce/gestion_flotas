<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingPoint extends Model
{
    protected $table = 'tracking_points';
    protected $primaryKey = 'id_tracking';
    public $timestamps = true;

    protected $fillable = [
        'id_jornada',
        'id_conductor',
        'timestamp_ubicacion',
        'geom',
        'velocidad',
        'heading',
        'precision',
        'bateria_porcentaje',
        'origen',
    ];

    protected $casts = [
        'velocidad' => 'float',
        'heading' => 'float',
        'precision' => 'float',
        'bateria_porcentaje' => 'integer',
    ];

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada', 'id_jornada');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }
}
