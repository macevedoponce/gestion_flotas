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
        'fotos_evidencia',
        'videos_evidencia',
        'evidencias_conductor',
        'observaciones_conductor',
        'notificado_conductor',
        'validado_por_proyecto',
        'fecha_validacion_proyecto',
        'comentarios_validacion_proyecto',
        'validado_por',
        'fecha_revision',
        'comentarios_revision',
        'estado',
        'ubicacion_entrega',
        'ubicacion_text',
    ];

    protected $casts = [
        'fotos_evidencia' => 'array',
        'videos_evidencia' => 'array',
        'evidencias_conductor' => 'array',
        'fecha_solicitud' => 'datetime',
        'fecha_validacion_proyecto' => 'datetime',
        'fecha_revision' => 'datetime',
    ];

    public function asignacion()
    {
        return $this->belongsTo(AsignacionVehiculo::class, 'id_asignacion', 'id_asignacion');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_usuario_solicitante');
    }

    public function validadorProyecto()
    {
        return $this->belongsTo(User::class, 'validado_por_proyecto');
    }

    public function validadorFinal()
    {
        return $this->belongsTo(User::class, 'validado_por');
    }
}
