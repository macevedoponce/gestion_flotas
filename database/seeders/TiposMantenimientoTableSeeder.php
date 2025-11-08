<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposMantenimientoTableSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'nombre' => 'Cambio de aceite',
                'descripcion' => 'Cambio de aceite y filtro'
            ],
            [
                'nombre' => 'Rotación de llantas',
                'descripcion' => 'Rotación y balanceo de llantas'
            ],
            [
                'nombre' => 'Frenos',
                'descripcion' => 'Revisión y cambio de pastillas de freno'
            ],
            [
                'nombre' => 'Alineación',
                'descripcion' => 'Alineación y balanceo'
            ],
            [
                'nombre' => 'Mantenimiento preventivo',
                'descripcion' => 'Mantenimiento programado según kilometraje'
            ],
            [
                'nombre' => 'Reparación mayor',
                'descripcion' => 'Reparación de motor o transmisión'
            ],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos_mantenimiento')->insert($tipo);
        }
    }
}