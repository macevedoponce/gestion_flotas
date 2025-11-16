<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    use HasFactory;

    protected $table = 'tipos_vehiculo';

    protected $primaryKey = 'id_tipo';

    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'capacidad_personas',
        'capacidad_tanque',
    ];

    protected $casts = [
        'capacidad_personas' => 'integer',
        'capacidad_tanque' => 'decimal:2',
    ];

    // ============================
    // RELACIONES
    // ============================

    // Vehículos registrados con este tipo
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_tipo_vehiculo');
    }

    // Plantillas de checklist asociadas
    public function checklistPlantillas()
    {
        return $this->hasMany(ChecklistPlantilla::class, 'id_tipo_vehiculo');
    }
}
