<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEventoEmergencia extends Model
{
    use HasFactory;

    protected $table = 'tipos_evento_emergencia';
    protected $primaryKey = 'id_tipo_evento';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
