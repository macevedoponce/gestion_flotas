<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingPoint extends Model
{
    protected $table = 'tracking_points';
    protected $primaryKey = 'id_tracking';
    public $timestamps = false;

    protected $fillable = [
        'id_jornada',
        'id_conductor',
        'timestamp_ubicacion',
        'geom',
        'velocidad',
        'heading',
        'precision',
        'bateria_porcentaje',
        'origen'
    ];

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor');
    }
}
