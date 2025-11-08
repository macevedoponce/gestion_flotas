<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    use HasFactory;

    protected $table = 'tipos_vehiculo';
    protected $primaryKey = 'id_tipo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'capacidad_personas',
        'capacidad_tanque'
    ];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_tipo_vehiculo', 'id_tipo_vehiculo');
    }
}
