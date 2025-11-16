<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jornada extends Model
{
    use HasFactory;

    protected $table = 'jornadas';
    protected $primaryKey = 'id_jornada';

    protected $fillable = [
        'id_asignacion',
        'id_conductor',
        'fecha_inicio',
        'fecha_fin',
        'dia_operativo',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'dia_operativo' => 'date',
    ];

    // ============================
    // RELACIONES
    // ============================

    // La asignación de la que depende la jornada
    public function asignacion()
    {
        return $this->belongsTo(AsignacionVehiculo::class, 'id_asignacion');
    }

    // Conductor que está trabajando
    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor');
    }

    // Reporte inicial
    public function reporteInicial()
    {
        return $this->hasOne(ReporteInicial::class, 'id_jornada');
    }

    // Reporte final
    public function reporteFinal()
    {
        return $this->hasOne(ReporteFinal::class, 'id_jornada');
    }

    // Abastecimientos dentro de la jornada
    public function abastecimientos()
    {
        return $this->hasMany(Abastecimiento::class, 'id_jornada');
    }

    // Tracking GPS del día
    public function tracking()
    {
        return $this->hasMany(TrackingPoint::class, 'id_jornada');
    }

    // ============================
    // SCOPES
    // ============================

    // Jornada activa (no finalizada)
    public function scopeActiva($query)
    {
        return $query->where('estado', 'EN_CURSO');
    }

    // Jornada cerrada
    public function scopeFinalizada($query)
    {
        return $query->where('estado', 'FINALIZADA');
    }

    // Jornada del día
    public function scopeDeHoy($query)
    {
        return $query->whereDate('dia_operativo', today());
    }

    // ============================
    // MÉTODOS DE ESTADO
    // ============================

    public function abrir()
    {
        $this->estado = 'EN_CURSO';
        $this->fecha_inicio = now();
        $this->dia_operativo = today();
        $this->save();
    }

    public function cerrar()
    {
        $this->estado = 'FINALIZADA';
        $this->fecha_fin = now();
        $this->save();
    }

    // ============================
    // LÓGICA DE NEGOCIO
    // ============================

    // Verifica si ya existe un reporte inicial
    public function tieneReporteInicial()
    {
        return $this->reporteInicial()->exists();
    }

    // Verifica si la jornada está lista para cerrar
    public function puedeFinalizar()
    {
        return $this->tieneReporteInicial() && !$this->reporteFinal;
    }

    public function estaAbierta()
    {
        return $this->estado === 'EN_CURSO';
    }
}
