<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Proyecto;
use App\Models\SolicitudVehiculo;

class SolicitudVehiculoTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener un jefe de proyecto o crear uno temporal si no existe
        $jefeProyecto = User::whereHas('roles', fn($q) => $q->where('name', 'Jefe de Proyecto'))->first();
        if (!$jefeProyecto) {
            $jefeProyecto = User::factory()->create([
                'name' => 'Jefe de Proyecto Temporal',
                'email' => 'jefe.proyecto@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Obtener proyectos existentes
        $proyecto1 = Proyecto::where('anexo', 'PROY-2024-001')->first();
        $proyecto2 = Proyecto::where('anexo', 'PROY-2024-002')->first();

        // Crear solicitudes de vehículo asociadas a proyectos reales
        SolicitudVehiculo::create([
            'id_proyecto' => $proyecto1?->id_proyecto ?? 1,
            'id_usuario_solicitante' => $jefeProyecto->id,
            'id_tipo_vehiculo' => 1,
            'motivo_trabajo' => 'Transporte de personal técnico para supervisión de obra',
            'lugar_trabajo' => 'Cusco',
            'cantidad_dias' => 9,
            'indeterminado' => false,
            'requiere_conductor' => true,
            'fecha_inicio' => now()->addDays(1),
            'fecha_fin' => now()->addDays(10),
            'estado' => 'PENDIENTE',
            'fecha_solicitud' => now(),
        ]);

        SolicitudVehiculo::create([
            'id_proyecto' => $proyecto2?->id_proyecto ?? 2,
            'id_usuario_solicitante' => $jefeProyecto->id,
            'id_tipo_vehiculo' => 2,
            'motivo_trabajo' => 'Supervisión de mantenimiento en zona rural',
            'lugar_trabajo' => 'Ayacucho',
            'cantidad_dias' => 4,
            'indeterminado' => false,
            'requiere_conductor' => false,
            'fecha_inicio' => now()->addDays(2),
            'fecha_fin' => now()->addDays(6),
            'estado' => 'PENDIENTE',
            'fecha_solicitud' => now(),
        ]);

        // Ejemplo adicional con conductor externo
        SolicitudVehiculo::create([
            'id_proyecto' => $proyecto1?->id_proyecto ?? 1,
            'id_usuario_solicitante' => $jefeProyecto->id,
            'id_tipo_vehiculo' => 3,
            'motivo_trabajo' => 'Traslado de materiales a almacén central',
            'lugar_trabajo' => 'Lima Norte',
            'cantidad_dias' => 3,
            'indeterminado' => false,
            'requiere_conductor' => false,
            'conductor_externo_nombres' => 'Juan Pérez Torres',
            'conductor_externo_dni' => '87654321',
            'conductor_externo_celular' => '987654321',
            'conductor_externo_licencia' => 'BIIA-456789',
            'fecha_inicio' => now()->addDays(5),
            'fecha_fin' => now()->addDays(8),
            'estado' => 'APROBADO',
            'fecha_solicitud' => now(),
        ]);
    }
}
