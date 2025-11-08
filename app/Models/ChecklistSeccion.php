<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistSeccion extends Model
{
    use HasFactory;

    protected $table = 'checklist_secciones';
    protected $primaryKey = 'id_seccion';
    protected $fillable = [
        'id_plantilla',
        'nombre',
        'descripcion',
        'orden',
        'activo',
    ];

    public function plantilla()
    {
        return $this->belongsTo(ChecklistPlantilla::class, 'id_plantilla');
    }

    public function items()
    {
        return $this->hasMany(ChecklistItem::class, 'id_seccion');
    }
}
