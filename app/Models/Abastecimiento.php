<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abastecimiento extends Model
{
    use SoftDeletes;

    protected $table = 'abastecimientos';
    protected $primaryKey = 'id_abastecimiento';

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

        'ubicacion',

        'estado_verificacion',
        'verificado_por',
        'observacion_verificacion',
        'fecha_verificacion',
    ];

    protected $casts = [
        'km_reportado' => 'float',
    ];

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada', 'id_jornada');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }

    public function verificador()
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }
}
