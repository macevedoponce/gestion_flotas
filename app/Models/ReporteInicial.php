<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteInicial extends Model
{
    use HasFactory;

    protected $table = 'reportes_iniciales';
    protected $primaryKey = 'id_reporte_inicial';

    protected $fillable = [
        'id_jornada',
        'km_inicial',
        'foto_km_inicial',
        'motivo_traslado',
        'destino',
        'cantidad_acompanantes',
        'acompanantes',
        'ubicacion_inicio',
        'checklist_completado',
        'fecha_reporte',
    ];

    protected $casts = [
        'fecha_reporte'       => 'datetime',
        'acompanantes'        => 'array',
        'ubicacion_inicio'    => 'array', // para manejar Point (GeoJSON API)
        'checklist_completado'=> 'boolean',
        'km_inicial'          => 'decimal:2',
    ];

    // ============================
    // RELACIONES
    // ============================

    // Pertenencia a una jornada
    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada');
    }

    // Respuestas de checklist asignado a este reporte inicial
    public function checklistRespuestas()
    {
        return $this->hasMany(ChecklistRespuesta::class, 'id_reporte_inicial');
    }

    // ============================
    // SCOPES
    // ============================

    public function scopeConChecklist($q)
    {
        return $q->where('checklist_completado', true);
    }

    // ============================
    // MÉTODOS DE NEGOCIO
    // ============================

    // Verifica si el reporte tiene checklist completo
    public function checklistCompleto()
    {
        return $this->checklist_completado === true;
    }

    // Cargar acompañantes de una manera segura
    public function registrarAcompanantes(array $data)
    {
        $this->acompanantes = $data;
        $this->cantidad_acompanantes = count($data);
        $this->save();
    }

    // Verificación si el reporte inicial está listo
    public function completo()
    {
        return $this->km_inicial !== null 
            && $this->foto_km_inicial !== null 
            && $this->checklist_completado === true;
    }
}
