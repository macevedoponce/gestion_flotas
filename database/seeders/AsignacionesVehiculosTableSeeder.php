<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AsignacionVehiculo;
use App\Models\SolicitudVehiculo;
use App\Models\Vehiculo;
use App\Models\Conductor;
use App\Models\User;
use App\Models\Proyecto;

class AsignacionesVehiculosTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener la primera solicitud existente
        $solicitud = SolicitudVehiculo::first();

        // Buscar al Jefe de Control y Monitoreo
        $jefeControl = User::whereHas('roles', fn ($q) => $q->where('name', 'Jefe de Control y Monitoreo'))->first();

        if ($solicitud) {
            // Crear o buscar proyecto vinculado al código de anexo
            $proyecto = Proyecto::firstOrCreate(
                ['codigo_anexo' => $solicitud->codigo_anexo],
                [
                    'descripcion' => 'Proyecto generado automáticamente desde la solicitud ' . $solicitud->codigo_anexo,
                    'responsable_id' => $jefeControl->id ?? 1,
                    'lugar_trabajo' => $solicitud->lugar_trabajo ?? 'Cusco',
                    'fecha_inicio' => $solicitud->fecha_inicio,
                    'fecha_fin' => $solicitud->fecha_fin,
                    'estado' => 'ACTIVO',
                ]
            );

            // Obtener vehículo y conductor disponibles
            $vehiculo = Vehiculo::first();
            $conductor = Conductor::where('estado_disponibilidad', 'DISPONIBLE')->first();

            // Crear la asignación de vehículo
            AsignacionVehiculo::create([
                'id_solicitud' => $solicitud->id_solicitud,
                'id_proyecto' => $proyecto->id_proyecto,
                'id_vehiculo' => $vehiculo->id_vehiculo ?? 1,
                'id_conductor' => $conductor->id_conductor ?? null,
                'id_jefe_control' => $jefeControl->id ?? 1,
                'fecha_asignacion' => now(),
                'fecha_finalizacion' => $solicitud->fecha_fin,
                'estado' => 'ACTIVA',
                'observaciones' => 'Asignación creada automáticamente desde el seeder para pruebas.',
            ]);
        }
    }
}
