<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChecklistSeccionesTableSeeder extends Seeder
{
    public function run(): void
    {
        $secciones = [
            // Secciones para plantilla 1 (Sedán)
            [
                'id_plantilla' => 1,
                'nombre' => 'Documentación',
                'descripcion' => 'Verificación de documentos del vehículo',
                'orden' => 1,
                'activo' => true,
            ],
            [
                'id_plantilla' => 1,
                'nombre' => 'Estado Exterior',
                'descripcion' => 'Inspección visual exterior del vehículo',
                'orden' => 2,
                'activo' => true,
            ],
            [
                'id_plantilla' => 1,
                'nombre' => 'Sistemas Internos',
                'descripcion' => 'Verificación de sistemas internos',
                'orden' => 3,
                'activo' => true,
            ],

            // Secciones para plantilla 2 (SUV)
            [
                'id_plantilla' => 2,
                'nombre' => 'Documentación y Equipos',
                'descripcion' => 'Documentos y equipos de seguridad',
                'orden' => 1,
                'activo' => true,
            ],
            [
                'id_plantilla' => 2,
                'nombre' => 'Inspección Mecánica',
                'descripcion' => 'Verificación de sistemas mecánicos',
                'orden' => 2,
                'activo' => true,
            ],
        ];

        foreach ($secciones as $seccion) {
            DB::table('checklist_secciones')->insert($seccion);
        }
    }
}