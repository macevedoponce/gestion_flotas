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

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function ceco()
    {
        return $this->belongsTo(Ceco::class, 'id_ceco');
    }

    // Relación con Solicitudes Vehículo (opcional)
    public function solicitudes()
    {
        return $this->hasMany(SolicitudVehiculo::class, 'id_proyecto');
    }
}
