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
        'codigo_anexo',
        'descripcion',
        'responsable_id',
        'lugar_trabajo',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id', 'id');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionVehiculo::class, 'id_proyecto');
    }
}
