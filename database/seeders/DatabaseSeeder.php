<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ConductoresTableSeeder::class,
            TiposVehiculoTableSeeder::class,
            TiposCombustibleTableSeeder::class,
            TiposMantenimientoTableSeeder::class,
            TiposEventoEmergenciaTableSeeder::class,
            VehiculosTableSeeder::class,
            ProyectosTableSeeder::class,
            SolicitudVehiculoTableSeeder::class,
            AsignacionesVehiculosTableSeeder::class,
            ChecklistPlantillasTableSeeder::class,
            ChecklistSeccionesTableSeeder::class,
            ChecklistItemsTableSeeder::class,
            JornadasTableSeeder::class,
            ReportesInicialesTableSeeder::class,
            ChecklistRespuestasTableSeeder::class,
            AbastecimientosTableSeeder::class,
            ReportesFinalesTableSeeder::class,
            TrackingPointsTableSeeder::class,
            SolicitudesDevolucionTableSeeder::class,
        ]);
    }
}