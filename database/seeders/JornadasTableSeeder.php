<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JornadasTableSeeder extends Seeder
{
    public function run(): void
    {
        $jornadas = [
            [
                'id_asignacion' => 1,
                'id_conductor' => 1,
                'fecha_inicio' => now()->subHours(2),
                'dia_operativo' => now()->toDateString(),
                'estado' => 'EN_CURSO',
                'observaciones' => 'Jornada para inspección de obras',
            ],
            [
                'id_asignacion' => 2,
                'id_conductor' => null, // Conductor externo
                'fecha_inicio' => now()->subDays(1)->setHour(8),
                'fecha_fin' => now()->subDays(1)->setHour(18),
                'dia_operativo' => now()->subDays(1)->toDateString(),
                'estado' => 'FINALIZADA',
                'observaciones' => 'Jornada completada para mantenimiento de redes',
            ],
            [
                'id_asignacion' => 1,
                'id_conductor' => 2,
                'fecha_inicio' => now()->subDays(2)->setHour(7),
                'fecha_fin' => now()->subDays(2)->setHour(17),
                'dia_operativo' => now()->subDays(2)->toDateString(),
                'estado' => 'FINALIZADA',
                'observaciones' => 'Transporte de personal técnico',
            ],
        ];

        foreach ($jornadas as $jornada) {
            DB::table('jornadas')->insert($jornada);
        }
    }
}