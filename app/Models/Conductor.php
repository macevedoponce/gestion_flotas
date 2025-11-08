<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    use HasFactory;

    protected $table = 'conductores';
    protected $primaryKey = 'id_conductor';

    protected $fillable = [
        'nombre_completo',
        'documento_identidad',
        'celular',
        'licencia_numero',
        'licencia_categoria',
        'licencia_vencimiento',
        'username_app',
        'password_hash',
        'estado_disponibilidad',
        'activo',
    ];

    protected $casts = [
        'licencia_vencimiento' => 'date',
        'activo' => 'boolean',
    ];

    /**
     * Scope para conductores activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para conductores disponibles
     */
    public function scopeDisponible($query)
    {
        return $query->where('estado_disponibilidad', 'DISPONIBLE')->activo();
    }

    /**
     * Verificar si la licencia está vencida
     */
    public function getLicenciaVencidaAttribute(): bool
    {
        return $this->licencia_vencimiento && $this->licencia_vencimiento->isPast();
    }

    /**
     * Verificar si tiene credenciales de app
     */
    public function getTieneAppAttribute(): bool
    {
        return !empty($this->username_app) && !empty($this->password_hash);
    }
}