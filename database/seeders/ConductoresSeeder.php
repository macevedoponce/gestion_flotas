<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConductoresSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('conductores')->insert([
            ['nombre_completo' => 'Juan Pérez', 'documento_identidad' => '12345678', 'estado_disponibilidad' => 'DISPONIBLE'],
            ['nombre_completo' => 'Luis Torres', 'documento_identidad' => '87654321', 'estado_disponibilidad' => 'DISPONIBLE'],
        ]);
    }
}

