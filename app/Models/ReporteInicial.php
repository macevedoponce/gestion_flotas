<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteInicial extends Model
{
    use HasFactory;

    protected $table = 'reportes_iniciales';
    protected $primaryKey = 'id_reporte_inicial';
    protected $fillable = [
        'id_jornada',
        'km_inicial',
        'foto_km_inicial',
        'motivo_traslado',
        'destino',
        'cantidad_acompanantes',
        'acompanantes',
        'ubicacion_inicio',
        'checklist_completado',
    ];

    protected $casts = [
        'acompanantes' => 'array',
    ];

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada');
    }

    public function checklistRespuestas()
    {
        return $this->hasMany(ChecklistRespuesta::class, 'id_reporte_inicial');
    }
}
