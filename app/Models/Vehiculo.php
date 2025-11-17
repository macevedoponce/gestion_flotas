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
        'activo',
    ];

    protected $casts = [
        'vencimiento_soat'     => 'date',
        'vencimiento_citv'     => 'date',
        'propio'               => 'boolean',
        'activo'               => 'boolean',
        'ubicacion_actual'     => 'array',
    ];

    // ============================
    // RELACIONES
    // ============================

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo');
    }

    public function tipoCombustible()
    {
        return $this->belongsTo(TipoCombustible::class, 'tipo_combustible_id');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionVehiculo::class, 'id_vehiculo');
    }

    public function jornadas()
    {
        return $this->hasMany(Jornada::class, 'id_vehiculo');
    }

    // ============================
    // SCOPES ÚTILES
    // ============================

    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'DISPONIBLE')->where('activo', true);
    }
}
