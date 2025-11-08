<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudDevolucion extends Model
{
    protected $table = 'solicitudes_devolucion';
    protected $primaryKey = 'id_devolucion';
    public $timestamps = false;

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
        'fotos_evidencia' => 'array',
        'videos_evidencia' => 'array',
    ];

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
}
