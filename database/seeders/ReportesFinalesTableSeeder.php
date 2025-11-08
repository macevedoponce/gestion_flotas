<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportesFinalesTableSeeder extends Seeder
{
    public function run(): void
    {
        $reportes = [
            [
                'id_jornada' => 2,
                'km_final' => 8180.50,
                'observaciones' => 'Jornada completada sin incidencias. Vehículo en buen estado.',
                'es_jornada_extendida' => false,
                'horas_totales' => 10.0,
            ],
            [
                'id_jornada' => 3,
                'km_final' => 15380.25,
                'observaciones' => 'Tráfico pesado en ruta de retorno. Vehículo requiere lavado.',
                'es_jornada_extendida' => true,
                'horas_totales' => 11.5,
            ],
        ];

        foreach ($reportes as $reporte) {
            DB::table('reportes_finales')->insert($reporte);
        }

        // Actualizar ubicaciones con SQL para PostGIS
        DB::statement("UPDATE reportes_finales SET ubicacion_fin = ST_GeogFromText('POINT(-77.0285 -12.0435)') WHERE id_reporte_final = 1");
        DB::statement("UPDATE reportes_finales SET ubicacion_fin = ST_GeogFromText('POINT(-77.0290 -12.0440)') WHERE id_reporte_final = 2");
    }
}