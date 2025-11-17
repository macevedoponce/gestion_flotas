<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEventoEmergencia extends Model
{
    protected $table = 'tipos_evento_emergencia';
    protected $primaryKey = 'id_tipo_evento';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
