<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SolicitudVehiculo;

class SolicitudVehiculoTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener al jefe de proyecto (si existe)
        $jefeProyecto = User::whereHas('roles', fn ($q) => $q->where('name', 'Jefe de Proyecto'))->first();

        SolicitudVehiculo::create([
            'codigo_anexo' => 'ANX-001',
            'descripcion_proyecto' => 'Solicitud para proyecto de campo en Cusco',
            'id_tipo_vehiculo' => 1,
            'motivo_trabajo' => 'Transporte de personal técnico',
            'lugar_trabajo' => 'Cusco',
            'fecha_inicio' => now()->addDays(1),
            'fecha_fin' => now()->addDays(10),
            'cantidad_dias' => 9,
            'requiere_conductor' => true,
            'indeterminado' => false,
            'estado' => 'PENDIENTE',
            'id_usuario_solicitante' => $jefeProyecto->id ?? 1,
            'fecha_solicitud' => now(),
        ]);

        SolicitudVehiculo::create([
            'codigo_anexo' => 'ANX-002',
            'descripcion_proyecto' => 'Visita a zona rural para inspección',
            'id_tipo_vehiculo' => 2,
            'motivo_trabajo' => 'Supervisión de infraestructura eléctrica',
            'lugar_trabajo' => 'Ayacucho',
            'fecha_inicio' => now()->addDays(2),
            'fecha_fin' => now()->addDays(6),
            'cantidad_dias' => 4,
            'requiere_conductor' => false,
            'indeterminado' => false,
            'estado' => 'PENDIENTE',
            'id_usuario_solicitante' => $jefeProyecto->id ?? 1,
            'fecha_solicitud' => now(),
        ]);
    }
}
