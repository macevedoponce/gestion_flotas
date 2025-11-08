<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportesInicialesTableSeeder extends Seeder
{
    public function run(): void
    {
        $reportes = [
            [
                'id_jornada' => 1,
                'km_inicial' => 15250.75,
                'motivo_traslado' => 'Inspección de obras de construcción en Lima Norte',
                'destino' => 'Av. Túpac Amaru - Independencia',
                'cantidad_acompanantes' => 2,
                'acompanantes' => json_encode([
                    ['nombre' => 'Ana Torres', 'dni' => '11223344', 'cargo' => 'Ingeniera Civil'],
                    ['nombre' => 'Luis Mendoza', 'dni' => '22334455', 'cargo' => 'Arquitecto']
                ]),
                'checklist_completado' => true,
            ],
            [
                'id_jornada' => 2,
                'km_inicial' => 8100.25,
                'motivo_traslado' => 'Mantenimiento de redes de agua potable',
                'destino' => 'Av. Larco - Miraflores',
                'cantidad_acompanantes' => 3,
                'acompanantes' => json_encode([
                    ['nombre' => 'Carlos Rojas', 'dni' => '33445566', 'cargo' => 'Técnico'],
                    ['nombre' => 'Marta Díaz', 'dni' => '44556677', 'cargo' => 'Supervisora'],
                    ['nombre' => 'Jorge Silva', 'dni' => '55667788', 'cargo' => 'Operario']
                ]),
                'checklist_completado' => true,
            ],
        ];

        foreach ($reportes as $reporte) {
            DB::table('reportes_iniciales')->insert($reporte);
        }

        // Actualizar ubicaciones con SQL para PostGIS
        DB::statement("UPDATE reportes_iniciales SET ubicacion_inicio = ST_GeogFromText('POINT(-77.0282 -12.0433)') WHERE id_reporte_inicial = 1");
        DB::statement("UPDATE reportes_iniciales SET ubicacion_inicio = ST_GeogFromText('POINT(-77.0319 -12.1210)') WHERE id_reporte_inicial = 2");
    }
}