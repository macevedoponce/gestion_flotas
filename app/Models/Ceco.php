<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ceco extends Model
{
    use HasFactory;

    protected $table = 'cecos';
    protected $primaryKey = 'id_ceco';

    protected $fillable = [
        'codigo_ceco',
        'descripcion_ceco',
        'responsable_id',
        'tipo_ceco',
    ];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'ceco_id', 'id_ceco');
    }
}
