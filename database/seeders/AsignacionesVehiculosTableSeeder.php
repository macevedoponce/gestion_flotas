<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignacionesVehiculosTableSeeder extends Seeder
{
    public function run(): void
    {
        $asignaciones = [
            [
                'id_solicitud' => 1, // SOL-2024-001
                'id_proyecto' => 1, // PROY-2024-001
                'id_vehiculo' => 1, // ABC-123
                'id_conductor' => 1, // Juan Pérez
                'id_jefe_control' => 2, // Supervisor Flota
                'fecha_asignacion' => now(),
                'estado' => 'ACTIVA',
                'observaciones' => 'Asignación para inspección de obras. Verificar combustible antes del viaje.',
            ],
            [
                'id_solicitud' => 2, // SOL-2024-002
                'id_proyecto' => 2, // PROY-2024-002
                'id_vehiculo' => 2, // DEF-456
                'id_conductor' => null, // Conductor externo
                'id_jefe_control' => 4, // Maria Coordinadora
                'fecha_asignacion' => now(),
                'fecha_finalizacion' => now()->addDays(5),
                'estado' => 'FINALIZADA',
                'observaciones' => 'Vehículo asignado con conductor externo. Entregar copia de licencia.',
            ],
        ];

        foreach ($asignaciones as $asignacion) {
            DB::table('asignaciones_vehiculos')->insert($asignacion);
        }
    }
}