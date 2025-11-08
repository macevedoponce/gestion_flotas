<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposVehiculoTableSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'nombre' => 'Sedán',
                'descripcion' => 'Vehículo de turismo estándar',
                'capacidad_personas' => 5,
                'capacidad_tanque' => 50.00,
            ],
            [
                'nombre' => 'SUV',
                'descripcion' => 'Vehículo utilitario deportivo',
                'capacidad_personas' => 7,
                'capacidad_tanque' => 70.00,
            ],
            [
                'nombre' => 'Camioneta',
                'descripcion' => 'Vehículo de carga ligera',
                'capacidad_personas' => 3,
                'capacidad_tanque' => 80.00,
            ],
            [
                'nombre' => 'Minivan',
                'descripcion' => 'Vehículo familiar',
                'capacidad_personas' => 8,
                'capacidad_tanque' => 60.00,
            ],
            [
                'nombre' => 'Motocicleta',
                'descripcion' => 'Vehículo de dos ruedas',
                'capacidad_personas' => 2,
                'capacidad_tanque' => 15.00,
            ],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos_vehiculo')->insert($tipo);
        }
    }
}