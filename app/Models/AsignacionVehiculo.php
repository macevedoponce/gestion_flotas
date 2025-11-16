<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionVehiculo extends Model
{
    use HasFactory;

    protected $table = 'asignaciones_vehiculos';
    protected $primaryKey = 'id_asignacion';

    protected $fillable = [
        'id_solicitud',
        'id_proyecto',
        'id_vehiculo',
        'id_conductor',
        'id_jefe_control',
        'fecha_asignacion',
        'fecha_finalizacion',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_finalizacion' => 'datetime',
    ];

    // =====================================================
    // RELACIONES
    // =====================================================

    public function solicitud()
    {
        return $this->belongsTo(SolicitudVehiculo::class, 'id_solicitud');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor');
    }

    public function jefeControl()
    {
        return $this->belongsTo(User::class, 'id_jefe_control');
    }

    // Jornadas (cada día una)
    public function jornadas()
    {
        return $this->hasMany(Jornada::class, 'id_asignacion');
    }

    // Solicitudes de devolución
    public function devoluciones()
    {
        return $this->hasMany(SolicitudDevolucion::class, 'id_asignacion');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeActivas($q)
    {
        return $q->where('estado', 'ACTIVA');
    }

    public function scopeFinalizadas($q)
    {
        return $q->where('estado', 'FINALIZADA');
    }

    // =====================================================
    // MÉTODOS DE NEGOCIO
    // =====================================================

    /**
     * Ejecuta la asignación formal
     */
    public function asignar()
    {
        // Cambiar vehículos
        $vehiculo = $this->vehiculo;
        if ($vehiculo) {
            $vehiculo->estado = 'ASIGNADO';
            $vehiculo->save();
        }

        // Conductor a "OCUPADO"
        $conductor = $this->conductor;
        if ($conductor) {
            $conductor->estado_disponibilidad = 'OCUPADO';
            $conductor->save();
        }

        // Cambiar estado de solicitud
        if ($this->solicitud) {
            $this->solicitud->estado = 'ASIGNADO';
            $this->solicitud->save();
        }

        $this->estado = 'ACTIVA';
        $this->fecha_asignacion = now();
        $this->save();
    }

    /**
     * Finaliza la asignación
     */
    public function finalizar()
    {
        $this->estado = 'FINALIZADA';
        $this->fecha_finalizacion = now();
        $this->save();

        // Vehículo queda en mantenimiento (lo cambia la devolución aprobada)
    }

    /**
     * Determina si la asignación puede generar jornadas
     */
    public function puedeIniciarJornada()
    {
        return $this->estado === 'ACTIVA'
            && $this->vehiculo?->estado === 'ASIGNADO'
            && $this->conductor?->estado_disponibilidad === 'OCUPADO';
    }

    /**
     * ¿Puede recibir solicitud de devolución?
     */
    public function admiteDevolucion()
    {
        return $this->estado === 'ACTIVA';
    }

    /**
     * Verifica si la asignación tiene una devolución pendiente
     */
    public function devolucionPendiente()
    {
        return $this->devoluciones()->where('estado', 'PENDIENTE')->exists();
    }

    /**
     * Verifica si conductor y vehículo siguen siendo coherentes
     */
    public function coherente()
    {
        return $this->vehiculo !== null && $this->conductor !== null;
    }
}
