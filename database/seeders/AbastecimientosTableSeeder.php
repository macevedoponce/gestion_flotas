<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbastecimientosTableSeeder extends Seeder
{
    public function run(): void
    {
        $abastecimientos = [
            [
                'id_jornada' => 1,
                'id_conductor' => 1,
                'fecha' => now()->subHour(),
                'km_reportado' => 15320.50,
                'estado_verificacion' => 'APROBADO',
                'verificado_por' => 2,
                'fecha_verificacion' => now()->subMinutes(30),
                'km_validado' => 15320.50,
                'litros_validado' => 25.50,
                'precio_total_validado' => 120.75,
                'codigo_comprobante' => 'F001-123456',
                'observaciones' => 'Abastecimiento aprobado sin observaciones',
            ],
            [
                'id_jornada' => 2,
                'id_conductor' => null,
                'fecha' => now()->subDays(1)->setHour(12),
                'km_reportado' => 8150.80,
                'estado_verificacion' => 'APROBADO',
                'verificado_por' => 4,
                'fecha_verificacion' => now()->subDays(1)->setHour(14),
                'km_validado' => 8150.80,
                'litros_validado' => 30.25,
                'precio_total_validado' => 145.30,
                'codigo_comprobante' => 'F001-123457',
                'observaciones' => 'Combustible para trabajos en Miraflores',
            ],
            [
                'id_jornada' => 1,
                'id_conductor' => 1,
                'fecha' => now(),
                'km_reportado' => 15400.25,
                'estado_verificacion' => 'PENDIENTE',
                'observaciones' => 'Esperando verificación del supervisor',
            ],
        ];

        foreach ($abastecimientos as $abastecimiento) {
            DB::table('abastecimientos')->insert($abastecimiento);
        }

        // Actualizar ubicaciones con SQL para PostGIS
        DB::statement("UPDATE abastecimientos SET ubicacion = ST_GeogFromText('POINT(-77.0350 -12.0560)') WHERE id_abastecimiento = 1");
        DB::statement("UPDATE abastecimientos SET ubicacion = ST_GeogFromText('POINT(-77.0330 -12.1250)') WHERE id_abastecimiento = 2");
        DB::statement("UPDATE abastecimientos SET ubicacion = ST_GeogFromText('POINT(-77.0400 -12.0600)') WHERE id_abastecimiento = 3");
    }
}