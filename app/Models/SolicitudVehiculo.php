<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitudVehiculo extends Model
{
    use HasFactory;

    protected $table = 'solicitud_vehiculo';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'id_usuario_solicitante',
        'id_proyecto',
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
        'estado',
        'conductor_creado_externo' => 'boolean',
    ];

    // =====================================================
    // RELACIONES
    // =====================================================

    /** Usuario solicitante */
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_usuario_solicitante', 'id');
    }

    /** Proyecto al que se solicita el vehículo */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    /** Tipo de vehículo requerido */
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo', 'id_tipo');
    }

    /** Asignación generada después de la aprobación */
    public function asignacion()
    {
        return $this->hasOne(AsignacionVehiculo::class, 'id_solicitud', 'id_solicitud');
    }

    /** Para saber si ya tuvo devoluciones */
    public function devoluciones()
    {
        // Aunque esto ya NO se usará aquí, lo dejamos para funciones generales
        return $this->hasMany(SolicitudDevolucion::class, 'id_solicitud', 'id_solicitud');
    }
}
