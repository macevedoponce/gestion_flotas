<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitudVehiculoTableSeeder extends Seeder
{
    public function run(): void
    {
        $solicitudes = [
            [
                'codigo_anexo' => 'SOL-2024-001',
                'descripcion_proyecto' => 'Proyecto de inspección de obras en Lima Norte',
                'id_usuario_solicitante' => 3, // Juan Solicitante
                'id_tipo_vehiculo' => 1, // Sedán
                'motivo_trabajo' => 'Transporte del equipo de ingenieros para inspección de obras',
                'lugar_trabajo' => 'Lima Norte - Independencia',
                'cantidad_dias' => 3,
                'indeterminado' => false,
                'requiere_conductor' => true,
                'estado' => 'APROBADA',
            ],
            [
                'codigo_anexo' => 'SOL-2024-002',
                'descripcion_proyecto' => 'Mantenimiento de redes en Miraflores',
                'id_usuario_solicitante' => 4, // Maria Coordinadora
                'id_tipo_vehiculo' => 2, // SUV
                'motivo_trabajo' => 'Transporte de herramientas y equipo para mantenimiento',
                'lugar_trabajo' => 'Miraflores - Lima',
                'cantidad_dias' => 5,
                'indeterminado' => false,
                'requiere_conductor' => false,
                'conductor_externo_nombres' => 'Roberto Externo',
                'conductor_externo_dni' => '44556677',
                'conductor_externo_celular' => '999555666',
                'conductor_externo_licencia' => 'E44556677',
                'estado' => 'PENDIENTE',
            ],
            [
                'codigo_anexo' => 'SOL-2024-003',
                'descripcion_proyecto' => 'Instalación de alumbrado público',
                'id_usuario_solicitante' => 3, // Juan Solicitante
                'id_tipo_vehiculo' => 4, // Minivan
                'motivo_trabajo' => 'Transporte de personal técnico y materiales',
                'lugar_trabajo' => 'San Juan de Lurigancho',
                'cantidad_dias' => 7,
                'indeterminado' => true,
                'requiere_conductor' => true,
                'estado' => 'RECHAZADA',
            ],
        ];

        foreach ($solicitudes as $solicitud) {
            DB::table('solicitud_vehiculo')->insert($solicitud);
        }
    }
}