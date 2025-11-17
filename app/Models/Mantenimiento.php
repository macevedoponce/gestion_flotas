<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $table = 'mantenimientos';
    protected $primaryKey = 'id_mantenimiento';

    protected $fillable = [
        'id_vehiculo',
        'tipo',
        'fecha_ingreso',
        'fecha_estimada_entrega',
        'fecha_entrega_real',
        'km_registrado',
        'taller_nombre',
        'taller_contacto',
        'motivo',
        'trabajos',
        'costo_estimado',
        'costo_real',
        'estado',
        'fecha_solicitud_prorroga',
        'motivo_prorroga',
        'nueva_fecha_entrega',
        'estado_prorroga',
        'fotos',
        'documentos',
        'creado_por',
        'aprobado_por',
    ];

    protected $casts = [
        'trabajos' => 'array',
        'fotos' => 'array',
        'documentos' => 'array',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}
