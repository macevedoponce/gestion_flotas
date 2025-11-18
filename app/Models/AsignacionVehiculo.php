<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\AsignacionLog;
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

        // Nuevos campos de recojo
        'evidencia_foto_km',
        'evidencia_foto_frontal',
        'evidencia_foto_posterior',
        'evidencia_foto_lat_izq',
        'evidencia_foto_lat_der',
        'evidencia_fotos_extra',
        'evidencia_observaciones',
        'evidencia_ubicacion_text',
        'evidencia_ubicacion',
    ];

    protected $casts = [
        'evidencia_fotos_extra' => 'array', // JSONB multiple
        'evidencia_ubicacion'   => 'array', // geography point (puede serializarse como array)
        'fecha_asignacion'      => 'datetime',
        'fecha_finalizacion'    => 'datetime',
    ];

    // -------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudVehiculo::class, 'id_solicitud', 'id_solicitud');
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function conductor(): BelongsTo
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }

    public function jefeControl(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_jefe_control', 'id');
    }
    public function devoluciones()
    {
        return $this->hasMany(SolicitudDevolucion::class, 'id_asignacion', 'id_asignacion');
    }
    public function logs()
    {
        return $this->hasMany(AsignacionLog::class, 'id_asignacion');
    }

}
