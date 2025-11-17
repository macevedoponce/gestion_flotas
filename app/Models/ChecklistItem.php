<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    protected $table = 'checklist_items';
    protected $primaryKey = 'id_item';

    protected $fillable = [
        'id_seccion',
        'id_tipo_pregunta',
        'pregunta',
        'obligatorio',
        'configuracion',
        'orden',
    ];

    protected $casts = [
        'configuracion' => 'array',
        'obligatorio' => 'boolean',
    ];

    public function seccion()
    {
        return $this->belongsTo(ChecklistSeccion::class, 'id_seccion', 'id_seccion');
    }

    public function tipoPregunta()
    {
        return $this->belongsTo(ChecklistTipoPregunta::class, 'id_tipo_pregunta', 'id_tipo_pregunta');
    }

    public function respuestas()
    {
        return $this->hasMany(ChecklistRespuesta::class, 'id_item', 'id_item');
    }
}
