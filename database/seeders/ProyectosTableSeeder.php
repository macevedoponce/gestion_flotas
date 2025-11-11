<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyectosTableSeeder extends Seeder
{
    public function run(): void
    {
        $proyectos = [
            [
                'ceco_id' => 1,
                'anexo' => 'PROY-2024-001',
                'anexo_descripcion' => 'Proyecto de construcción de infraestructura vial en Lima Norte',
                'encargado_id' => 1,
                'region' => 'Lima Norte',
                'unidad_negocio' => 'Infraestructura',
                'tipo_flujo' => 'OPEX',
                'proyecto' => 'Construcción vial Lima Norte',
                'fecha_inicio' => '2024-01-15',
                'fecha_fin' => '2024-12-15',
                'estado' => 'ACTIVO',
            ],
            [
                'ceco_id' => 2,
                'anexo' => 'PROY-2024-002',
                'anexo_descripcion' => 'Mantenimiento de redes de agua en Miraflores',
                'encargado_id' => 1,
                'region' => 'Lima Sur',
                'unidad_negocio' => 'Servicios Hidráulicos',
                'tipo_flujo' => 'OPEX',
                'proyecto' => 'Mantenimiento redes de agua',
                'fecha_inicio' => '2024-03-01',
                'fecha_fin' => '2024-08-30',
                'estado' => 'ACTIVO',
            ],
            [
                'ceco_id' => 3,
                'anexo' => 'PROY-2024-003',
                'anexo_descripcion' => 'Instalación de alumbrado público en San Juan de Lurigancho',
                'encargado_id' => 1,
                'region' => 'Lima Este',
                'unidad_negocio' => 'Energía y Alumbrado',
                'tipo_flujo' => 'CAPEX',
                'proyecto' => 'Alumbrado SJL',
                'fecha_inicio' => '2024-02-10',
                'fecha_fin' => '2024-06-30',
                'estado' => 'SUSPENDIDO',
            ],
        ];

        DB::table('proyectos')->insert($proyectos);
    }
}
