<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReporteFinal extends Model
{
    use SoftDeletes;

    protected $table = 'reportes_finales';
    protected $primaryKey = 'id_reporte_final';

    protected $fillable = [
        'id_jornada',
        'km_final',
        'foto_km_final',
        'fotos_adicionales',
        'observaciones',
        'ubicacion_fin',
        'horas_totales',

        'km_validado',
        'estado_validacion',
        'observacion_validacion',
        'validado_por',
        'validado_en',
    ];

    protected $casts = [
        'fotos_adicionales' => 'array',
        'horas_totales' => 'float',
        'km_final' => 'float',
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
}
