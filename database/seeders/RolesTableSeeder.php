<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso completo al sistema'
            ],
            [
                'nombre' => 'Control y Monitoreo',
                'descripcion' => 'Conductor de vehículos'
            ],
            [
                'nombre' => 'Jefe de proyecto',
                'descripcion' => 'Supervisor de flota vehicular'
            ],
            [
                'nombre' => 'Jefe de control y monitoreo',
                'descripcion' => 'Usuario estándar del sistema'
            ],
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->insert($rol);
        }
    }
}