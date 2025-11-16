<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';
    protected $primaryKey = 'id_vehiculo';

    protected $fillable = [
        'placa',
        'id_tipo_vehiculo',
        'marca',
        'modelo',
        'numero_serie',
        'numero_motor',
        'color',
        'anio',
        'vencimiento_soat',
        'vencimiento_citv',
        'tipo_combustible_id',
        'km_actual',
        'estado',
        'propio',
        'foto_soat',
        'foto_citv',
        'foto_tarjeta_propiedad',
        'ubicacion_actual', // geography(Point,4326)
        'activo'
    ];

    protected $casts = [
        'vencimiento_soat' => 'date',
        'vencimiento_citv' => 'date',
        'km_actual' => 'decimal:2',
        'propio' => 'boolean',
        'activo' => 'boolean',
        // Mantiene el raw de geographic, la app utilizará WKT/GeoJSON
        'ubicacion_actual' => 'string',
    ];

    // ============================
    // RELACIONES
    // ============================

    // Tipo de vehículo (camioneta, auto, cisterna, etc.)
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo');
    }

    // Combustible (gasolina, diesel, glp...)
    public function tipoCombustible()
    {
        return $this->belongsTo(TipoCombustible::class, 'tipo_combustible_id');
    }

    // Asignaciones del vehículo
    public function asignaciones()
    {
        return $this->hasMany(AsignacionVehiculo::class, 'id_vehiculo');
    }

    // Última asignación activa
    public function asignacionActiva()
    {
        return $this->hasOne(AsignacionVehiculo::class, 'id_vehiculo')
            ->where('estado', 'ACTIVA');
    }

    // Jornadas (movimientos diarios)
    public function jornadas()
    {
        return $this->hasManyThrough(
            Jornada::class,
            AsignacionVehiculo::class,
            'id_vehiculo',
            'id_asignacion',
            'id_vehiculo',
            'id_asignacion'
        );
    }

    // ============================
    // SCOPES ÚTILES
    // ============================

    // Vehículos disponibles para asignación
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'DISPONIBLE')->where('activo', true);
    }

    // Solo activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Filtrar por tipo de vehículo
    public function scopeTipo($query, $tipoId)
    {
        return $query->where('id_tipo_vehiculo', $tipoId);
    }
}
