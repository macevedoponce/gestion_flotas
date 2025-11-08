<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCombustible extends Model
{
    use HasFactory;

    protected $table = 'tipos_combustible';
    protected $primaryKey = 'id_tipo_combustible';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_tipo_combustible', 'id_tipo_combustible');
    }
}
