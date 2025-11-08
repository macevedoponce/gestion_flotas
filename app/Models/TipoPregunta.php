<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoPregunta extends Model
{
    use HasFactory;

    protected $table = 'tipos_pregunta';
    protected $primaryKey = 'id_tipo_pregunta';
    protected $fillable = [
        'nombre',
        'estructura_respuesta',
    ];

    public function items()
    {
        return $this->hasMany(ChecklistItem::class, 'id_tipo_pregunta');
    }
}
