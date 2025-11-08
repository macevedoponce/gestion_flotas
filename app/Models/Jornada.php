<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jornada extends Model
{
    use HasFactory;

    protected $table = 'jornadas';
    protected $primaryKey = 'id_jornada';
    protected $fillable = [
        'id_asignacion',
        'id_conductor',
        'fecha_inicio',
        'fecha_fin',
        'dia_operativo',
        'estado',
        'observaciones',
    ];

    public function asignacion()
    {
        return $this->belongsTo(AsignacionVehiculo::class, 'id_asignacion');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor');
    }

    public function reporteInicial()
    {
        return $this->hasOne(ReporteInicial::class, 'id_jornada');
    }

    public function reportesFinales()
    {
        return $this->hasMany(ReporteFinal::class, 'id_jornada');
    }

    public function abastecimientos()
    {
        return $this->hasMany(Abastecimiento::class, 'id_jornada');
    }

    public function checklistRespuestas()
    {
        return $this->hasMany(ChecklistRespuesta::class, 'id_jornada');
    }
}
