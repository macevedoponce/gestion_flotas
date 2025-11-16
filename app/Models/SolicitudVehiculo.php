<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudVehiculo extends Model
{
    use HasFactory;

    protected $table = 'solicitud_vehiculo';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'codigo_anexo',
        'descripcion_proyecto',
        'id_usuario_solicitante',
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
        'estado',
    ];

    protected $casts = [
        'indeterminado' => 'boolean',
        'requiere_conductor' => 'boolean',
        'cantidad_dias' => 'integer',
    ];

    // ============================
    // RELACIONES
    // ============================

    // Usuario solicitante
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_usuario_solicitante');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }

    // Tipo de vehículo solicitado
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo');
    }

    // Asignación (puede ser null hasta que la atienda Control y Monitoreo)
    public function asignacion()
    {
        return $this->hasOne(AsignacionVehiculo::class, 'id_solicitud');
    }

    // Devolución asociada a esta solicitud (vía asignación)
    public function devolucion()
    {
        return $this->hasOneThrough(
            SolicitudDevolucion::class,
            AsignacionVehiculo::class,
            'id_solicitud',     // asignaciones.id_solicitud
            'id_asignacion',    // devoluciones.id_asignacion
            'id_solicitud',
            'id_asignacion'
        );
    }

    // ============================
    // SCOPES ÚTILES
    // ============================

    public function scopePendientes($query)
    {
        return $query->where('estado', 'PENDIENTE');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'APROBADA');
    }

    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'RECHAZADA');
    }

    public function scopeAsignadas($query)
    {
        return $query->where('estado', 'ASIGNADA');
    }

    // ============================
    // MÉTODOS DE ESTADO
    // ============================

    public function estaPendiente()
    {
        return $this->estado === 'PENDIENTE';
    }

    public function estaAsignada()
    {
        return $this->estado === 'ASIGNADA';
    }

    public function estaAprobada()
    {
        return $this->estado === 'APROBADA';
    }

    public function requiereConductorExterno()
    {
        return $this->requiere_conductor === false;
    }
}
