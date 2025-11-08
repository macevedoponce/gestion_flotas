<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChecklistItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Items para sección 1 (Documentación - Sedán)
            [
                'id_seccion' => 1,
                'id_tipo_pregunta' => 1, // si_no
                'pregunta' => '¿SOAT vigente?',
                'descripcion' => 'Verificar fecha de vencimiento del SOAT',
                'obligatorio' => true,
                'orden' => 1,
                'activo' => true,
            ],
            [
                'id_seccion' => 1,
                'id_tipo_pregunta' => 1, // si_no
                'pregunta' => '¿Tarjeta de propiedad vigente?',
                'descripcion' => 'Verificar documentación del vehículo',
                'obligatorio' => true,
                'orden' => 2,
                'activo' => true,
            ],
            [
                'id_seccion' => 1,
                'id_tipo_pregunta' => 9, // nivel_combustible
                'pregunta' => 'Nivel de combustible',
                'descripcion' => 'Registrar nivel actual de combustible',
                'obligatorio' => true,
                'orden' => 3,
                'configuracion' => json_encode(['min' => 0, 'max' => 100, 'unidad' => '%']),
                'activo' => true,
            ],

            // Items para sección 2 (Estado Exterior - Sedán)
            [
                'id_seccion' => 2,
                'id_tipo_pregunta' => 1, // si_no
                'pregunta' => '¿Luces funcionando correctamente?',
                'descripcion' => 'Verificar todas las luces: delanteras, traseras, intermitentes',
                'obligatorio' => true,
                'orden' => 1,
                'activo' => true,
            ],
            [
                'id_seccion' => 2,
                'id_tipo_pregunta' => 1, // si_no
                'pregunta' => '¿Presión de llantas correcta?',
                'descripcion' => 'Verificar presión en las 4 llantas',
                'obligatorio' => true,
                'orden' => 2,
                'activo' => true,
            ],
            [
                'id_seccion' => 2,
                'id_tipo_pregunta' => 6, // imagen
                'pregunta' => 'Foto del estado general',
                'descripcion' => 'Tomar foto del estado general del vehículo',
                'obligatorio' => false,
                'orden' => 3,
                'activo' => true,
            ],

            // Items para sección 3 (Sistemas Internos - Sedán)
            [
                'id_seccion' => 3,
                'id_tipo_pregunta' => 1, // si_no
                'pregunta' => '¿Frenos funcionando correctamente?',
                'descripcion' => 'Probar frenos de mano y de pie',
                'obligatorio' => true,
                'orden' => 1,
                'activo' => true,
            ],
            [
                'id_seccion' => 3,
                'id_tipo_pregunta' => 4, // numero
                'pregunta' => 'Kilometraje actual',
                'descripcion' => 'Registrar kilometraje del tablero',
                'obligatorio' => true,
                'orden' => 2,
                'configuracion' => json_encode(['min' => 0, 'max' => 999999]),
                'activo' => true,
            ],
            [
                'id_seccion' => 3,
                'id_tipo_pregunta' => 7, // firma
                'pregunta' => 'Firma del conductor',
                'descripcion' => 'Firma confirmando la verificación',
                'obligatorio' => true,
                'orden' => 3,
                'activo' => true,
            ],
        ];

        foreach ($items as $item) {
            DB::table('checklist_items')->insert($item);
        }
    }
}