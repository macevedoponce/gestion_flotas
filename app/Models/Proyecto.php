<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';
    protected $primaryKey = 'id_proyecto';

    protected $fillable = [
        'codigo_anexo',
        'descripcion',
        'responsable_id',
        'id_ceco',
        'lugar_trabajo',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    // ============================
    // RELACIONES
    // ============================

    // CECO al que pertenece
    public function ceco()
    {
        return $this->belongsTo(Ceco::class, 'id_ceco');
    }

    // Responsable del proyecto
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    // Asignaciones relacionadas al proyecto
    public function asignaciones()
    {
        return $this->hasMany(AsignacionVehiculo::class, 'id_proyecto');
    }

    // Vehículos asignados al proyecto
    public function vehiculos()
    {
        return $this->hasManyThrough(
            Vehiculo::class,
            AsignacionVehiculo::class,
            'id_proyecto',    // Foreign key in asignaciones
            'id_vehiculo',    // Foreign key in vehiculos
            'id_proyecto',    // Local key in proyectos
            'id_vehiculo'     // Local key in asignaciones
        );
    }

    // Conductores que trabajan en el proyecto
    public function conductores()
    {
        return $this->hasManyThrough(
            Conductor::class,
            AsignacionVehiculo::class,
            'id_proyecto',
            'id_conductor',
            'id_proyecto',
            'id_conductor'
        );
    }

    // Solicitudes asociadas al proyecto (a través de la asignación)
    public function solicitudes()
    {
        return $this->hasManyThrough(
            SolicitudVehiculo::class,
            AsignacionVehiculo::class,
            'id_proyecto',
            'id_solicitud',
            'id_proyecto',
            'id_solicitud'
        );
    }

    // ============================
    // SCOPES ÚTILES
    // ============================

    public function scopeActivos($query)
    {
        return $query->where('estado', 'ACTIVO');
    }
}
