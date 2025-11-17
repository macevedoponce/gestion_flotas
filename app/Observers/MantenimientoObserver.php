<?php

namespace App\Observers;

use App\Models\Mantenimiento;
use App\Models\Vehiculo;

class MantenimientoObserver
{
    public function created(Mantenimiento $mantenimiento)
    {
        $this->actualizarEstadoVehiculo($mantenimiento);
    }

    public function updated(Mantenimiento $mantenimiento)
    {
        $this->actualizarEstadoVehiculo($mantenimiento);
    }

    private function actualizarEstadoVehiculo(Mantenimiento $mantenimiento)
    {
        $vehiculo = $mantenimiento->vehiculo;

        if (!$vehiculo) {
            return;
        }

        // A) Estados en los que el vehículo DEBE estar bloqueado
        $estadosBloqueo = [
            'EN_PROCESO',
            'PRORROGA_SOLICITADA',
            'PROGRAMADO',
        ];

        // B) Estado FINALIZADO → liberar vehículo
        if ($mantenimiento->estado === 'FINALIZADO') {
            $vehiculo->estado = 'DISPONIBLE';
            $vehiculo->save();
            return;
        }

        // C) Si está en cualquier estado de bloqueo
        if (in_array($mantenimiento->estado, $estadosBloqueo)) {
            $vehiculo->estado = 'EN_MANTENIMIENTO';
            $vehiculo->save();
        }
    }
}
