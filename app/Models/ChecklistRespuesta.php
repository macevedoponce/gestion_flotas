<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistRespuesta extends Model
{
    use HasFactory;

    protected $table = 'checklist_respuestas';
    protected $fillable = [
        'id_reporte_inicial',
        'id_item',
        'valor_texto',
        'valor_numero',
        'valor_booleano',
        'valor_fecha',
        'valor_json',
        'valor_imagen',
    ];

    protected $casts = [
        'valor_json' => 'array',
    ];

    public function reporteInicial()
    {
        return $this->belongsTo(ReporteInicial::class, 'id_reporte_inicial');
    }

    public function item()
    {
        return $this->belongsTo(ChecklistItem::class, 'id_item');
    }
}
