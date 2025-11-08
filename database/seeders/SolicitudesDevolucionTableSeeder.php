<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitudesDevolucionTableSeeder extends Seeder
{
    public function run(): void
    {
        $solicitudes = [
            [
                'id_asignacion' => 2,
                'id_usuario_solicitante' => 4,
                'fecha_solicitud' => now()->subDays(1)->setHour(19),
                'fotos_evidencia' => json_encode([
                    'foto_delantero.jpg',
                    'foto_trasero.jpg',
                    'foto_interior.jpg'
                ]),
                'ubicacion_text' => 'Oficina Central - Parque Industrial',
                'observaciones' => 'Vehículo devuelto en perfecto estado. Llaves entregadas en recepción.',
                'estado' => 'APROBADA',
                'validado_por' => 2,
                'fecha_revision' => now()->subDays(1)->setHour(20),
                'comentarios_revision' => 'Devolución verificada. Vehículo en óptimas condiciones.',
            ],
            [
                'id_asignacion' => 1,
                'id_usuario_solicitante' => 3,
                'fecha_solicitud' => now(),
                'ubicacion_text' => 'Av. Túpac Amaru - Independencia',
                'observaciones' => 'Solicitud de devolución por finalización de jornada diaria',
                'estado' => 'PENDIENTE',
            ],
        ];

        foreach ($solicitudes as $solicitud) {
            DB::table('solicitudes_devolucion')->insert($solicitud);
        }

        // Actualizar ubicaciones con SQL para PostGIS
        DB::statement("UPDATE solicitudes_devolucion SET ubicacion_entrega = ST_GeogFromText('POINT(-77.0280 -12.0430)') WHERE id_devolucion = 1");
        DB::statement("UPDATE solicitudes_devolucion SET ubicacion_entrega = ST_GeogFromText('POINT(-77.0285 -12.0435)') WHERE id_devolucion = 2");
    }
}