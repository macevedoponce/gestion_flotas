<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDevolucion extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_devolucion';
    protected $primaryKey = 'id_devolucion';

    protected $fillable = [
        'id_asignacion',
        'id_usuario_solicitante',
        'fecha_solicitud',
        'fotos_evidencia',
        'videos_evidencia',
        'ubicacion_entrega',
        'ubicacion_text',
        'observaciones',
        'estado',
        'validado_por',
        'fecha_revision',
        'comentarios_revision',
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_revision'   => 'datetime',

        // FileUpload (multiple) → JSON
        'fotos_evidencia'   => 'json',
        'videos_evidencia'  => 'json',

        // ⚠️ Geographic field cannot be cast to array
        // 'ubicacion_entrega' => 'string',
    ];

    // ============================
    // RELACIONES
    // ============================

    public function asignacion()
    {
        return $this->belongsTo(AsignacionVehiculo::class, 'id_asignacion');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_usuario_solicitante');
    }

    public function validador()
    {
        return $this->belongsTo(User::class, 'validado_por');
    }

    // ============================
    // SCOPES
    // ============================

    public function scopePendientes($q)
    {
        return $q->where('estado', 'PENDIENTE');
    }

    public function scopeAprobadas($q)
    {
        return $q->where('estado', 'APROBADA');
    }

    // ============================
    // MÉTODOS DE NEGOCIO
    // ============================

    public function completa()
    {
        return !empty($this->fotos_evidencia)
            && $this->ubicacion_entrega !== null;
    }

    public function aprobar(string $comentarios = null)
    {
        $this->estado = 'APROBADA';
        $this->validado_por = auth()->id();
        $this->fecha_revision = now();
        $this->comentarios_revision = $comentarios;
        $this->save();

        $this->cerrarAsignacion();
    }

    public function rechazar(string $comentarios)
    {
        $this->estado = 'RECHAZADA';
        $this->validado_por = auth()->id();
        $this->fecha_revision = now();
        $this->comentarios_revision = $comentarios;
        $this->save();
    }

    private function cerrarAsignacion()
    {
        $asignacion = $this->asignacion;

        if (!$asignacion) return;

        $asignacion->estado = 'FINALIZADA';
        $asignacion->fecha_finalizacion = now();
        $asignacion->save();

        // Vehículo pasa a mantenimiento
        $vehiculo = $asignacion->vehiculo;
        if ($vehiculo) {
            $vehiculo->estado = 'MANTENIMIENTO';
            $vehiculo->save();
        }

        // Conductor queda libre
        $conductor = $asignacion->conductor;
        if ($conductor) {
            $conductor->estado_disponibilidad = 'DISPONIBLE';
            $conductor->save();
        }
    }

    public function getGeoJson()
    {
        return null; // Será implementado cuando procesemos ST_AsGeoJSON
    }
}
