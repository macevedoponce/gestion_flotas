<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChecklistRespuestasTableSeeder extends Seeder
{
    public function run(): void
    {
        $respuestas = [
            // Respuestas para reporte inicial 1
            [
                'id_reporte_inicial' => 1,
                'id_item' => 1, // ¿SOAT vigente?
                'valor_booleano' => true,
            ],
            [
                'id_reporte_inicial' => 1,
                'id_item' => 2, // ¿Tarjeta de propiedad vigente?
                'valor_booleano' => true,
            ],
            [
                'id_reporte_inicial' => 1,
                'id_item' => 3, // Nivel de combustible
                'valor_json' => json_encode(['valor' => 85, 'unidad' => '%']),
            ],
            [
                'id_reporte_inicial' => 1,
                'id_item' => 4, // ¿Luces funcionando correctamente?
                'valor_booleano' => true,
            ],
            [
                'id_reporte_inicial' => 1,
                'id_item' => 5, // ¿Presión de llantas correcta?
                'valor_booleano' => true,
            ],
            [
                'id_reporte_inicial' => 1,
                'id_item' => 7, // ¿Frenos funcionando correctamente?
                'valor_booleano' => true,
            ],
            [
                'id_reporte_inicial' => 1,
                'id_item' => 8, // Kilometraje actual
                'valor_numero' => 15250.75,
            ],

            // Respuestas para reporte inicial 2
            [
                'id_reporte_inicial' => 2,
                'id_item' => 1, // ¿SOAT vigente?
                'valor_booleano' => true,
            ],
            [
                'id_reporte_inicial' => 2,
                'id_item' => 2, // ¿Tarjeta de propiedad vigente?
                'valor_booleano' => true,
            ],
            [
                'id_reporte_inicial' => 2,
                'id_item' => 3, // Nivel de combustible
                'valor_json' => json_encode(['valor' => 70, 'unidad' => '%']),
            ],
            [
                'id_reporte_inicial' => 2,
                'id_item' => 4, // ¿Luces funcionando correctamente?
                'valor_booleano' => true,
            ],
            [
                'id_reporte_inicial' => 2,
                'id_item' => 5, // ¿Presión de llantas correcta?
                'valor_booleano' => true,
            ],
            [
                'id_reporte_inicial' => 2,
                'id_item' => 7, // ¿Frenos funcionando correctamente?
                'valor_booleano' => true,
            ],
            [
                'id_reporte_inicial' => 2,
                'id_item' => 8, // Kilometraje actual
                'valor_numero' => 8100.25,
            ],
        ];

        foreach ($respuestas as $respuesta) {
            DB::table('checklist_respuestas')->insert($respuesta);
        }
    }
}