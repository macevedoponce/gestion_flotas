<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abastecimiento extends Model
{
    use HasFactory;

    protected $table = 'abastecimientos';
    protected $fillable = [
        'id_jornada',
        'id_conductor',
        'fecha',
        'km_reportado',
        'foto_tablero_antes',
        'foto_surtidor_cero',
        'foto_tablero_despues',
        'foto_surtidor_final',
        'foto_comprobante',
        'estado_verificacion',
        'verificado_por',
        'fecha_verificacion',
        'km_validado',
        'litros_validado',
        'precio_total_validado',
        'codigo_comprobante',
        'observaciones',
    ];

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor');
    }

    public function verificador()
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }
}
