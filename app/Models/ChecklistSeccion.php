<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistSeccion extends Model
{
    protected $table = 'checklist_secciones';
    protected $primaryKey = 'id_seccion';

    protected $fillable = [
        'id_plantilla',
        'nombre',
        'orden',
    ];

    public function plantilla()
    {
        return $this->belongsTo(ChecklistPlantilla::class, 'id_plantilla', 'id_plantilla');
    }

    public function items()
    {
        return $this->hasMany(ChecklistItem::class, 'id_seccion', 'id_seccion');
    }
}
