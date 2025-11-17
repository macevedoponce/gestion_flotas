<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jornada extends Model
{
    use SoftDeletes;

    protected $table = 'jornadas';
    protected $primaryKey = 'id_jornada';

    protected $fillable = [
        'id_asignacion',
        'id_conductor',
        'dia_operativo',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'observaciones',
    ];

    public function asignacion()
    {
        return $this->belongsTo(AsignacionVehiculo::class, 'id_asignacion', 'id_asignacion');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }

    public function reportesIniciales()
    {
        return $this->hasMany(ReporteInicial::class, 'id_jornada', 'id_jornada');
    }

    public function reportesFinales()
    {
        return $this->hasMany(ReporteFinal::class, 'id_jornada', 'id_jornada');
    }

    public function abastecimientos()
    {
        return $this->hasMany(Abastecimiento::class, 'id_jornada', 'id_jornada');
    }
}
