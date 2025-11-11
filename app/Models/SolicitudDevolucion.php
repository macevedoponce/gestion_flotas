<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDevolucion extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_devolucion';
    protected $primaryKey = 'id_devolucion';

    protected $casts = [
        'fotos_evidencia' => 'array',
        'videos_evidencia' => 'array',
    ];

    protected $fillable = [
        'id_asignacion',
        'id_usuario_solicitante',
        'fotos_evidencia',
        'videos_evidencia',
        'ubicacion_text',
        'observaciones',
        'estado',
        'validado_por',
        'fecha_revision',
        'comentarios_revision',
    ];

    public function asignacion()
    {
        return $this->belongsTo(AsignacionVehiculo::class, 'id_asignacion', 'id_asignacion');
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
