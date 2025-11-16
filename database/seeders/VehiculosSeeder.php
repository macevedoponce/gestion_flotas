<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vehiculos')->insert([
            [
                'placa' => 'ABC-123',
                'id_tipo_vehiculo' => 1,
                'marca' => 'Toyota',
                'modelo' => 'Hilux',
                'color' => 'Blanco',
                'anio' => 2022,
                'tipo_combustible_id' => 2,
                'estado' => 'DISPONIBLE',
            ],
            [
                'placa' => 'XYZ-987',
                'id_tipo_vehiculo' => 2,
                'marca' => 'Hyundai',
                'modelo' => 'Elantra',
                'color' => 'Gris',
                'anio' => 2021,
                'tipo_combustible_id' => 1,
                'estado' => 'DISPONIBLE',
            ],
        ]);
    }
}

