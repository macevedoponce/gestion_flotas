<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudDevolucion extends Model
{
    protected $table = 'solicitudes_devolucion';
    protected $primaryKey = 'id_devolucion';

    protected $fillable = [
        'id_asignacion',
        'id_usuario_solicitante',
        'fecha_solicitud',
        'comentario_solicitante',

        'id_conductor',
        'evidencia_foto_km_dev',
        'evidencia_foto_frontal_dev',
        'evidencia_foto_posterior_dev',
        'evidencia_foto_lat_izq_dev',
        'evidencia_foto_lat_der_dev',
        'evidencia_fotos_extra_dev',
        'evidencia_observaciones_dev',
        'evidencia_ubicacion_text_dev',
        'evidencia_ubicacion_dev',
        'fecha_evidencias_conductor',

        'id_usuario_valida_proyecto',
        'comentario_valida_proyecto',
        'fecha_valida_proyecto',

        'id_usuario_valida_control',
        'comentario_valida_control',
        'fecha_valida_control',

        'estado',
    ];

    protected $casts = [
        'evidencia_fotos_extra_dev' => 'array',
        'fecha_solicitud' => 'datetime',
        'fecha_evidencias_conductor' => 'datetime',
        'fecha_valida_proyecto' => 'datetime',
        'fecha_valida_control' => 'datetime',
    ];

    public function asignacion()
    {
        return $this->belongsTo(AsignacionVehiculo::class, 'id_asignacion', 'id_asignacion');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_usuario_solicitante');
    }

    public function revisorProyecto()
    {
        return $this->belongsTo(User::class, 'id_usuario_valida_proyecto');
    }

    public function revisorControl()
    {
        return $this->belongsTo(User::class, 'id_usuario_valida_control');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }
}
