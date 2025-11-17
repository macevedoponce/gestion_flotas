<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReporteInicial extends Model
{
    use SoftDeletes;

    protected $table = 'reportes_iniciales';
    protected $primaryKey = 'id_reporte_inicial';

    protected $fillable = [
        'id_jornada',
        'km_inicial',
        'foto_km_inicial',
        'motivo_traslado',
        'destino',
        'acompanantes',
        'ubicacion_inicio',
        'checklist_completado',

        'km_validado',
        'estado_validacion',
        'observacion_validacion',
        'validado_por',
        'validado_en',
    ];

    protected $casts = [
        'acompanantes' => 'array',
        'checklist_completado' => 'boolean',
        'km_inicial' => 'float',
        'km_validado' => 'float',
    ];

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada', 'id_jornada');
    }

    public function validador()
    {
        return $this->belongsTo(User::class, 'validado_por');
    }

    public function checklistEjecucion()
{
    return $this->hasOne(ChecklistEjecucion::class, 'id_reporte_inicial', 'id_reporte_inicial');
}
}
