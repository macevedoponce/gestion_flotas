<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AsignacionVehiculo;
use App\Models\Conductor;

class JornadasTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos algunas asignaciones y conductores existentes
        $asignacion1 = AsignacionVehiculo::first();
        $asignacion2 = AsignacionVehiculo::skip(1)->first(); // La segunda, si existe

        $conductor1 = Conductor::first();
        $conductor2 = Conductor::skip(1)->first();

        // Si no hay asignaciones, no seguimos
        if (!$asignacion1) {
            $this->command->warn('No se encontraron asignaciones en la base de datos. Ejecuta primero AsignacionesVehiculosTableSeeder.');
            return;
        }

        $jornadas = [
            [
                'id_asignacion' => $asignacion1->id_asignacion,
                'id_conductor' => $conductor1->id_conductor ?? null,
                'fecha_inicio' => now()->subHours(2),
                'dia_operativo' => now()->toDateString(),
                'estado' => 'EN_CURSO',
                'observaciones' => 'Jornada para inspección de obras',
            ],
            [
                'id_asignacion' => $asignacion2->id_asignacion ?? $asignacion1->id_asignacion,
                'id_conductor' => $conductor2->id_conductor ?? null,
                'fecha_inicio' => now()->subDays(1)->setHour(8),
                'fecha_fin' => now()->subDays(1)->setHour(18),
                'dia_operativo' => now()->subDays(1)->toDateString(),
                'estado' => 'FINALIZADA',
                'observaciones' => 'Jornada completada para mantenimiento de redes',
            ],
            [
                'id_asignacion' => $asignacion1->id_asignacion,
                'id_conductor' => $conductor2->id_conductor ?? null,
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

        $this->command->info('✅ Jornadas insertadas correctamente.');
    }
}
