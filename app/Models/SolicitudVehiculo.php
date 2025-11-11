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
        'id_proyecto',
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
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo', 'id_tipo');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_usuario_solicitante');
    }

    public function asignacion()
    {
        return $this->hasOne(AsignacionVehiculo::class, 'id_solicitud', 'id_solicitud');
    }

    public function devoluciones()
    {
        return $this->hasMany(SolicitudDevolucion::class, 'id_asignacion', 'id_solicitud');
    }
}
