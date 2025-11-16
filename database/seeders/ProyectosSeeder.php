<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyectosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('proyectos')->insert([
            [
                'codigo_anexo' => 'PROY-001',
                'descripcion' => 'Infraestructura Vial Lima Norte',
                'responsable_id' => 1,
                'id_ceco' => 1,
                'lugar_trabajo' => 'Lima Norte',
                'estado' => 'ACTIVO',
            ],
        ]);
    }
}

