<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $table = 'checklist_items';
    protected $primaryKey = 'id_item';
    protected $fillable = [
        'id_seccion',
        'id_tipo_pregunta',
        'pregunta',
        'descripcion',
        'obligatorio',
        'orden',
        'configuracion',
        'activo',
    ];

    protected $casts = [
        'configuracion' => 'array',
    ];

    public function seccion()
    {
        return $this->belongsTo(ChecklistSeccion::class, 'id_seccion');
    }

    public function tipoPregunta()
    {
        return $this->belongsTo(TipoPregunta::class, 'id_tipo_pregunta');
    }
}
