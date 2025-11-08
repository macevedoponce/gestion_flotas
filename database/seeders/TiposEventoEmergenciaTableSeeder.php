<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposEventoEmergenciaTableSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'nombre' => 'Accidente de tránsito',
                'descripcion' => 'Colisión o choque vehicular'
            ],
            [
                'nombre' => 'Avería mecánica',
                'descripcion' => 'Falla en el sistema mecánico del vehículo'
            ],
            [
                'nombre' => 'Emergencia médica',
                'descripcion' => 'Problema de salud del conductor o pasajero'
            ],
            [
                'nombre' => 'Robo o hurto',
                'descripcion' => 'Sustracción del vehículo o pertenencias'
            ],
            [
                'nombre' => 'Incidente climático',
                'descripcion' => 'Problemas por condiciones climáticas adversas'
            ],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos_evento_emergencia')->insert($tipo);
        }
    }
}