<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'ubicacion_actual',
        'estado',
        'propio',
        'foto_soat',
        'foto_citv',
        'foto_tarjeta_propiedad',
        'activo',
    ];

    protected $casts = [
        'anio' => 'integer',
        'vencimiento_soat' => 'date',
        'vencimiento_citv' => 'date',
        'km_actual' => 'decimal:2',
        'propio' => 'boolean',
        'activo' => 'boolean',
        'fecha_creacion' => 'datetime',
    ];

    /**
     * Relación con el tipo de vehículo
     */
    public function tipoVehiculo(): BelongsTo
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo', 'id_tipo');
    }

    /**
     * Relación con el tipo de combustible
     */
    public function tipoCombustible(): BelongsTo
    {
        return $this->belongsTo(TipoCombustible::class, 'tipo_combustible_id', 'id_tipo_combustible');
    }

    /**
     * Scope para vehículos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para vehículos disponibles
     */
    public function scopeDisponible($query)
    {
        return $query->where('estado', 'DISPONIBLE')->activo();
    }

    /**
     * Scope para vehículos propios
     */
    public function scopePropio($query)
    {
        return $query->where('propio', true);
    }

    /**
     * Verificar si el SOAT está vencido
     */
    public function getSoatVencidoAttribute(): bool
    {
        return $this->vencimiento_soat && $this->vencimiento_soat->isPast();
    }

    /**
     * Verificar si el CITV está vencido
     */
    public function getCitvVencidoAttribute(): bool
    {
        return $this->vencimiento_citv && $this->vencimiento_citv->isPast();
    }
}