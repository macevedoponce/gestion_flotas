<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteFinal extends Model
{
    use HasFactory;

    protected $table = 'reportes_finales';
    protected $fillable = [
        'id_jornada',
        'km_final',
        'foto_km_final',
        'fotos_adicionales',
        'ubicacion_fin',
        'observaciones',
        'es_jornada_extendida',
        'horas_totales',
    ];

    protected $casts = [
        'fotos_adicionales' => 'array',
    ];

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada');
    }
}
