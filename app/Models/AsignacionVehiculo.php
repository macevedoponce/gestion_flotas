<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    public function solicitud()
    {
        return $this->belongsTo(SolicitudVehiculo::class, 'id_solicitud', 'id_solicitud');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }

    public function jefeControl()
    {
        return $this->belongsTo(User::class, 'id_jefe_control');
    }

    public function devoluciones()
    {
        return $this->hasMany(SolicitudDevolucion::class, 'id_asignacion', 'id_asignacion');
    }
}
