<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistPlantilla extends Model
{
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
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo', 'id_tipo');
    }

    public function secciones()
    {
        return $this->hasMany(ChecklistSeccion::class, 'id_plantilla', 'id_plantilla');
    }

    public function items()
    {
        return $this->hasManyThrough(
            ChecklistItem::class,
            ChecklistSeccion::class,
            'id_plantilla',   // FK en ChecklistSeccion
            'id_seccion',     // FK en ChecklistItem
            'id_plantilla',   // PK en ChecklistPlantilla
            'id_seccion'      // PK en ChecklistSeccion
        );
    }

    public function ejecuciones()
    {
        return $this->hasMany(ChecklistEjecucion::class, 'id_plantilla', 'id_plantilla');
    }
}
