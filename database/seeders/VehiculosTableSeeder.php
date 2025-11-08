<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculosTableSeeder extends Seeder
{
    public function run(): void
    {
        $vehiculos = [
            [
                'placa' => 'ABC-123',
                'id_tipo_vehiculo' => 1, // Sedán
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'numero_serie' => '1HGBH41JXMN109186',
                'numero_motor' => '2ZR1234567',
                'color' => 'Blanco',
                'anio' => 2022,
                'vencimiento_soat' => '2024-12-31',
                'vencimiento_citv' => '2024-06-30',
                'tipo_combustible_id' => 3, // Gasolina 95
                'km_actual' => 15000.50,
                'estado' => 'DISPONIBLE',
                'propio' => true,
                'activo' => true,
            ],
            [
                'placa' => 'DEF-456',
                'id_tipo_vehiculo' => 2, // SUV
                'marca' => 'Nissan',
                'modelo' => 'X-Trail',
                'numero_serie' => '5N1AR2MMXEC123456',
                'numero_motor' => 'QR25234567',
                'color' => 'Negro',
                'anio' => 2023,
                'vencimiento_soat' => '2025-03-15',
                'vencimiento_citv' => '2024-09-20',
                'tipo_combustible_id' => 5, // Diésel B5
                'km_actual' => 8000.25,
                'estado' => 'DISPONIBLE',
                'propio' => true,
                'activo' => true,
            ],
            [
                'placa' => 'GHI-789',
                'id_tipo_vehiculo' => 4, // Minivan
                'marca' => 'Kia',
                'modelo' => 'Carnival',
                'numero_serie' => 'KNALW4C15B5123456',
                'numero_motor' => 'G4KE123456',
                'color' => 'Gris',
                'anio' => 2021,
                'vencimiento_soat' => '2024-08-10',
                'vencimiento_citv' => '2024-02-28',
                'tipo_combustible_id' => 3, // Gasolina 95
                'km_actual' => 25000.75,
                'estado' => 'MANTENIMIENTO',
                'propio' => false,
                'activo' => true,
            ],
        ];

        foreach ($vehiculos as $vehiculo) {
            DB::table('vehiculos')->insert($vehiculo);
        }
    }
}