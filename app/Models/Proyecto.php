<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';
    protected $primaryKey = 'id_proyecto';

    protected $fillable = [
        'ceco_id',
        'anexo',
        'anexo_descripcion',
        'encargado_id',
        'region',
        'unidad_negocio',
        'tipo_flujo',
        'proyecto',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    public function ceco()
    {
        return $this->belongsTo(Ceco::class, 'ceco_id', 'id_ceco');
    }

    public function encargado()
    {
        return $this->belongsTo(User::class, 'encargado_id');
    }
}
