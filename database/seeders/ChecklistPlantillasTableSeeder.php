<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChecklistPlantillasTableSeeder extends Seeder
{
    public function run(): void
    {
        $plantillas = [
            [
                'nombre' => 'Checklist Pre-Operacional Sedán',
                'descripcion' => 'Checklist para vehículos tipo sedán antes de cada viaje',
                'id_tipo_vehiculo' => 1, // Sedán
                'activo' => true,
            ],
            [
                'nombre' => 'Checklist Pre-Operacional SUV',
                'descripcion' => 'Checklist para vehículos tipo SUV antes de cada viaje',
                'id_tipo_vehiculo' => 2, // SUV
                'activo' => true,
            ],
            [
                'nombre' => 'Checklist General Vehículos',
                'descripcion' => 'Checklist general aplicable a todos los tipos de vehículos',
                'id_tipo_vehiculo' => null, // Para todos los tipos
                'activo' => true,
            ],
        ];

        foreach ($plantillas as $plantilla) {
            DB::table('checklist_plantillas')->insert($plantilla);
        }
    }
}