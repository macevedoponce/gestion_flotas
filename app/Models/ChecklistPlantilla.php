<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistPlantilla extends Model
{
    use HasFactory;

    protected $table = 'checklist_plantillas';
    protected $primaryKey = 'id_plantilla';
    protected $fillable = [
        'nombre',
        'descripcion',
        'id_tipo_vehiculo',
        'activo',
    ];

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo');
    }

    public function secciones()
    {
        return $this->hasMany(ChecklistSeccion::class, 'id_plantilla');
    }
}
