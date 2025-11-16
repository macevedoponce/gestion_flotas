<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposBasicosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipos_vehiculo')->insert([
            ['nombre' => 'Camioneta', 'capacidad_personas' => 5],
            ['nombre' => 'Sedán', 'capacidad_personas' => 4],
            ['nombre' => 'Camión', 'capacidad_personas' => 2],
        ]);

        DB::table('tipos_combustible')->insert([
            ['nombre' => 'Gasolina'],
            ['nombre' => 'Diésel'],
            ['nombre' => 'GLP'],
        ]);
    }
}

