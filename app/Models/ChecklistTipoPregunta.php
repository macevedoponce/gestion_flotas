<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistTipoPregunta extends Model
{
    protected $table = 'checklist_tipos_pregunta';
    protected $primaryKey = 'id_tipo_pregunta';

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    public function items()
    {
        return $this->hasMany(ChecklistItem::class, 'id_tipo_pregunta', 'id_tipo_pregunta');
    }
}
