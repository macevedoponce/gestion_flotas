<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    use HasFactory;

    protected $table = 'conductores';
    protected $primaryKey = 'id_conductor';

    protected $fillable = [
        'nombre_completo',
        'documento_identidad',
        'celular',
        'licencia_numero',
        'licencia_categoria',
        'licencia_vencimiento',
        'username_app',
        'password_hash',
        'estado_disponibilidad',
        'activo',
        'tipo_conductor',
    ];

    protected $casts = [
        'licencia_vencimiento' => 'date',
        'activo' => 'boolean',
    ];

    // ============================
    // RELACIONES
    // ============================

    // Asignaciones en las que participa
    public function asignaciones()
    {
        return $this->hasMany(AsignacionVehiculo::class, 'id_conductor');
    }

    // Jornadas laborales del conductor
    public function jornadas()
    {
        return $this->hasMany(Jornada::class, 'id_conductor');
    }

    // Abastecimientos reportados por el conductor
    public function abastecimientos()
    {
        return $this->hasMany(Abastecimiento::class, 'id_conductor');
    }

    // Tracking GPS
    public function trackingPoints()
    {
        return $this->hasMany(TrackingPoint::class, 'id_conductor');
    }

    // ============================
    // SCOPES ÚTILES
    // ============================

    // Conductores disponibles para una asignación
    public function scopeDisponibles($query)
    {
        return $query
            ->where('estado_disponibilidad', 'DISPONIBLE')
            ->where('activo', true)
            ->where('tipo_conductor', 'INTERNO');
    }

    // Conductores activos en el sistema
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Conductores con licencia vigente
    public function scopeLicenciaVigente($query)
    {
        return $query->whereDate('licencia_vencimiento', '>=', today());
    }

    // ============================
    // MÉTODOS ÚTILES
    // ============================

    // Verifica si puede recibir asignación
    public function estaDisponible()
    {
        return $this->estado_disponibilidad === 'DISPONIBLE' && $this->activo;
    }

    // Define si es usuario externo (registrado por solicitud)
    public function esExterno()
    {
        return empty($this->username_app) && empty($this->password_hash);
    }
}
