<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistEjecucion extends Model
{
    protected $table = 'checklist_ejecuciones';
    protected $primaryKey = 'id_ejecucion';

    protected $fillable = [
        'id_plantilla',
        'id_reporte_inicial',
        'id_jornada',
        'id_vehiculo',
        'id_conductor',
        'fecha_ejecucion',
        'estado',
    ];

    public function plantilla()
    {
        return $this->belongsTo(ChecklistPlantilla::class, 'id_plantilla', 'id_plantilla');
    }

    public function reporteInicial()
    {
        return $this->belongsTo(ReporteInicial::class, 'id_reporte_inicial', 'id_reporte_inicial');
    }

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada', 'id_jornada');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }

    public function respuestas()
    {
        return $this->hasMany(ChecklistRespuesta::class, 'id_ejecucion', 'id_ejecucion');
    }
}
