<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyectosTableSeeder extends Seeder
{
    public function run(): void
    {
        $proyectos = [
            [
                'codigo_anexo' => 'PROY-2024-001',
                'descripcion' => 'Proyecto de construcción de infraestructura vial en Lima Norte',
                'responsable_id' => 1,
                'lugar_trabajo' => 'Lima Norte - Independencia',
                'fecha_inicio' => '2024-01-15',
                'fecha_fin' => '2024-12-15',
                'estado' => 'ACTIVO',
            ],
            [
                'codigo_anexo' => 'PROY-2024-002',
                'descripcion' => 'Mantenimiento de redes de agua en distrito de Miraflores',
                'responsable_id' => 1,
                'lugar_trabajo' => 'Miraflores - Lima',
                'fecha_inicio' => '2024-03-01',
                'fecha_fin' => '2024-08-30',
                'estado' => 'ACTIVO',
            ],
            [
                'codigo_anexo' => 'PROY-2024-003',
                'descripcion' => 'Instalación de alumbrado público en San Juan de Lurigancho',
                'responsable_id' => 1,
                'lugar_trabajo' => 'San Juan de Lurigancho',
                'fecha_inicio' => '2024-02-10',
                'fecha_fin' => '2024-06-30',
                'estado' => 'SUSPENDIDO',
            ],
        ];

        foreach ($proyectos as $proyecto) {
            DB::table('proyectos')->insert($proyecto);
        }
    }
}