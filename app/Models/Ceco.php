<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ceco extends Model
{
    use HasFactory;

    protected $table = 'cecos';
    protected $primaryKey = 'id_ceco';

    // Campos que pueden asignarse masivamente
    protected $fillable = [
        'codigo',
        'descripcion',
    ];

    // ============================
    // RELACIONES
    // ============================

    // Un CECO tiene muchos proyectos
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id_ceco');
    }

    // ============================
    // SCOPES ÚTILES
    // ============================

    public function scopePorCodigo($query, $codigo)
    {
        return $query->where('codigo', $codigo);
    }
}
