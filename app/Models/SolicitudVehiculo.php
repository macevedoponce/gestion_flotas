<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SolicitudVehiculo extends Model
{
    use HasFactory;

    protected $table = 'solicitud_vehiculo';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'codigo_anexo',
        'descripcion_proyecto',
        'id_usuario_solicitante',
        'fecha_solicitud',
        'id_tipo_vehiculo',
        'motivo_trabajo',
        'lugar_trabajo',
        'cantidad_dias',
        'indeterminado',
        'requiere_conductor',
        'conductor_externo_nombres',
        'conductor_externo_dni',
        'conductor_externo_celular',
        'conductor_externo_licencia',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'indeterminado' => 'boolean',
        'requiere_conductor' => 'boolean',
        'cantidad_dias' => 'integer',
    ];

    protected $attributes = [
        'estado' => 'PENDIENTE',
        'indeterminado' => false,
        'requiere_conductor' => true,
    ];

    /**
     * Relación con el usuario solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_usuario_solicitante', 'id');
    }

    /**
     * Relación con el tipo de vehículo
     */
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo', 'id_tipo');
    }

    /**
     * Relación con las asignaciones de vehículos
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionVehiculo::class, 'id_solicitud', 'id_solicitud');
    }

    /**
     * Scope para solicitudes pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'PENDIENTE');
    }

    /**
     * Scope para solicitudes aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'APROBADA');
    }

    /**
     * Scope para solicitudes rechazadas
     */
    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'RECHAZADA');
    }

    /**
     * Scope para solicitudes activas (fecha inicio <= hoy <= fecha fin o indeterminadas)
     */
    public function scopeActivas($query)
    {
        return $query->where(function ($q) {
            $q->where('indeterminado', true)
              ->orWhere(function ($q2) {
                  $q2->where('fecha_inicio', '<=', now())
                     ->where('fecha_fin', '>=', now());
              });
        });
    }

    /**
     * Verifica si la solicitud está pendiente
     */
    public function estaPendiente(): bool
    {
        return $this->estado === 'PENDIENTE';
    }

    /**
     * Verifica si la solicitud está aprobada
     */
    public function estaAprobada(): bool
    {
        return $this->estado === 'APROBADA';
    }

    /**
     * Verifica si la solicitud está activa
     */
    public function estaActiva(): bool
    {
        if ($this->indeterminado) {
            return true;
        }

        if (!$this->fecha_inicio || !$this->fecha_fin) {
            return false;
        }

        $now = now();
        return $now->between($this->fecha_inicio, $this->fecha_fin);
    }

    /**
     * Calcula la cantidad de días automáticamente si no está establecida
     */
    public function calcularCantidadDias(): ?int
    {
        if ($this->indeterminado) {
            return null;
        }

        if ($this->fecha_inicio && $this->fecha_fin) {
            return $this->fecha_inicio->diffInDays($this->fecha_fin);
        }

        return $this->cantidad_dias;
    }

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Calcular automáticamente la cantidad de días antes de guardar
        static::saving(function ($model) {
            if (!$model->indeterminado && $model->fecha_inicio && $model->fecha_fin) {
                $model->cantidad_dias = $model->fecha_inicio->diffInDays($model->fecha_fin);
            } elseif ($model->indeterminado) {
                $model->cantidad_dias = null;
                $model->fecha_fin = null;
            }
        });

        // Establecer fecha_solicitud automáticamente al crear
        static::creating(function ($model) {
            if (empty($model->fecha_solicitud)) {
                $model->fecha_solicitud = now();
            }
        });
    }
}