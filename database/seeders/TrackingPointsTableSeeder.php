<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrackingPointsTableSeeder extends Seeder
{
    public function run(): void
    {
        $trackingPoints = [
            [
                'id_jornada' => 1,
                'id_conductor' => 1,
                'timestamp_ubicacion' => now()->subHours(2),
                'velocidad' => 0.0,
                'heading' => 0.0,
                'precision' => 5.0,
                'bateria_porcentaje' => 85,
                'origen' => 'APP',
                'geom' => DB::raw("ST_GeogFromText('POINT(-77.0282 -12.0433)')"),
            ],
            [
                'id_jornada' => 1,
                'id_conductor' => 1,
                'timestamp_ubicacion' => now()->subHours(1)->subMinutes(45),
                'velocidad' => 45.5,
                'heading' => 180.5,
                'precision' => 8.2,
                'bateria_porcentaje' => 82,
                'origen' => 'APP',
                'geom' => DB::raw("ST_GeogFromText('POINT(-77.0300 -12.0500)')"),
            ],
            [
                'id_jornada' => 1,
                'id_conductor' => 1,
                'timestamp_ubicacion' => now()->subHours(1)->subMinutes(30),
                'velocidad' => 60.2,
                'heading' => 175.3,
                'precision' => 10.5,
                'bateria_porcentaje' => 80,
                'origen' => 'APP',
                'geom' => DB::raw("ST_GeogFromText('POINT(-77.0320 -12.0550)')"),
            ],
            [
                'id_jornada' => 2,
                'id_conductor' => null,
                'timestamp_ubicacion' => now()->subDays(1)->setHour(9),
                'velocidad' => 35.8,
                'heading' => 90.0,
                'precision' => 6.8,
                'bateria_porcentaje' => 78,
                'origen' => 'APP',
                'geom' => DB::raw("ST_GeogFromText('POINT(-77.0330 -12.1200)')"),
            ],
        ];

        foreach ($trackingPoints as $point) {
            DB::table('tracking_points')->insert($point);
        }
    }
}