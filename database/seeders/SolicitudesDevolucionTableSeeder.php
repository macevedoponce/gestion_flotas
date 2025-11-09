<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudDevolucion;
use App\Models\AsignacionVehiculo;
use App\Models\User;


class SolicitudesDevolucionTableSeeder extends Seeder
{
    public function run(): void
    {
        $asignacion = AsignacionVehiculo::first();
        $jefeProyecto = User::whereHas('roles', fn ($q) => $q->where('name', 'Jefe de Proyecto'))->first();
        $validador = User::whereHas('roles', fn ($q) => $q->where('name', 'Jefe de Control y Monitoreo'))->first();

        if ($asignacion) {
            SolicitudDevolucion::create([
                'id_asignacion' => $asignacion->id_asignacion,
                'id_usuario_solicitante' => $jefeProyecto->id ?? 1,
                'fecha_solicitud' => now(),
                'ubicacion_text' => 'Base Cusco',
                'observaciones' => 'Vehículo en buen estado',
                'estado' => 'VALIDADA',
                'validado_por' => $validador->id ?? 1,
                'fecha_revision' => now(),
                'comentarios_revision' => 'Todo correcto, devolución aceptada',
            ]);
        }
    }
}