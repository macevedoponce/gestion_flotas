<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1) Spatie roles y permisos
            RolesAndPermissionsSeeder::class,

            // 2) Usuarios del sistema
            UserSeeder::class,

            // 3) Catálogos básicos (vehículos, combustibles, mantenimiento, etc.)
            TiposBasicosSeeder::class,

            // 4) Conductores iniciales
            ConductoresSeeder::class,

            // 5) Vehículos de prueba
            VehiculosSeeder::class,

            // 6) Centros de costo
            CecosSeeder::class,

            // 7) Proyectos (requieren usuarios + cecos obligatoriamente)
            ProyectosSeeder::class,
        ]);
    }
}
