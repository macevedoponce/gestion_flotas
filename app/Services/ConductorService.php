<?php

namespace App\Services;

use App\Models\Conductor;
use App\Models\SolicitudVehiculo;

class ConductorService
{
    public function crearDesdeSolicitud(SolicitudVehiculo $solicitud): Conductor
    {
        return Conductor::create([
            'nombre_completo'       => $solicitud->conductor_externo_nombres,
            'documento_identidad'   => $solicitud->conductor_externo_dni,
            'celular'               => $solicitud->conductor_externo_celular,
            'licencia_numero'       => $solicitud->conductor_externo_licencia,
            'licencia_categoria'    => 'NO_ESPECIFICA',
            'licencia_vencimiento'  => now()->addYear(), // placeholder
            'username_app'          => $solicitud->conductor_externo_dni,
            'password_hash'         => $solicitud->conductor_externo_dni,
            'estado_disponibilidad' => 'DISPONIBLE',
            'activo'                => true,
            'tipo_conductor'        => 'EXTERNO',
        ]);
    }
}
