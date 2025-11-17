<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistRespuesta extends Model
{
    protected $table = 'checklist_respuestas';
    protected $primaryKey = 'id_respuesta';

    protected $fillable = [
        'id_ejecucion',
        'id_item',
        'valor_texto',
        'valor_numero',
        'valor_booleano',
        'valor_json',
        'valor_imagen',
    ];

    protected $casts = [
        'valor_json' => 'array',
        'valor_booleano' => 'boolean',
    ];

    public function ejecucion()
    {
        return $this->belongsTo(ChecklistEjecucion::class, 'id_ejecucion', 'id_ejecucion');
    }

    public function item()
    {
        return $this->belongsTo(ChecklistItem::class, 'id_item', 'id_item');
    }
}
