<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventoEmergencia extends Model
{
    use SoftDeletes;

    protected $table = 'eventos_emergencia';
    protected $primaryKey = 'id_evento';

    protected $fillable = [
        'id_jornada',
        'id_conductor',
        'id_vehiculo',
        'id_tipo_evento',

        'descripcion',
        'fotos',
        'ubicacion',
        'hora_reporte',

        'estado',
        'atendido_por',
        'comentario_cierre',
        'hora_cierre',
    ];

    protected $casts = [
        'fotos' => 'array',
    ];

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada', 'id_jornada');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function tipoEvento()
    {
        return $this->belongsTo(TipoEventoEmergencia::class, 'id_tipo_evento', 'id_tipo_evento');
    }

    public function atendidoPor()
    {
        return $this->belongsTo(User::class, 'atendido_por');
    }
}
