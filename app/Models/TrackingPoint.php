<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingPoint extends Model
{
    use HasFactory;

    protected $table = 'tracking_points';
    protected $primaryKey = 'id_tracking';

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
        'timestamp_ubicacion' => 'datetime',
        'geom' => 'array', // GeoJSON para API
        'velocidad' => 'decimal:2',
        'precision' => 'decimal:2',
        'heading' => 'decimal:2',
        'bateria_porcentaje' => 'integer',
    ];

    // ============================
    // RELACIONES
    // ============================

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor');
    }

    // ============================
    // SCOPES
    // ============================

    // puntos de una jornada
    public function scopePorJornada($q, $idJornada)
    {
        return $q->where('id_jornada', $idJornada);
    }

    // puntos recientes (últimos X minutos)
    public function scopeRecientes($q, $minutos = 10)
    {
        return $q->where('timestamp_ubicacion', '>=', now()->subMinutes($minutos));
    }

    // ============================
    // MÉTODOS DE NEGOCIO
    // ============================

    // Determina si el punto es válido por precisión
    public function esGpsConfiable()
    {
        return $this->precision !== null && $this->precision <= 25; // metros
    }

    // Velocidad en km/h como número legible
    public function velocidadKmh()
    {
        return $this->velocidad ?? 0;
    }

    // ¿El conductor está detenido?
    public function detenido()
    {
        return $this->velocidad <= 2;
    }

    // ¿El conductor excedió límites?
    public function excesoVelocidad()
    {
        return $this->velocidad > 90; // configurable
    }

    // Convertir el geometry a GeoJSON estándar
    public function getGeoJson()
    {
        return [
            'type' => 'Point',
            'coordinates' => [
                $this->geom['coordinates'][0],
                $this->geom['coordinates'][1],
            ],
        ];
    }
}
