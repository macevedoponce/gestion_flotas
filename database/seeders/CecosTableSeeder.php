<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CecosTableSeeder extends Seeder
{
    public function run(): void
    {
        $cecos = [
            [
                'codigo_ceco' => '0101001001',
                'descripcion_ceco' => 'GERENCIA',
                'responsable_id' => 1,
                'tipo_ceco' => 'OPEX',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo_ceco' => '0101001002',
                'descripcion_ceco' => 'ADMINISTRACIÓN',
                'responsable_id' => 1,
                'tipo_ceco' => 'OPEX',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo_ceco' => '0101001003',
                'descripcion_ceco' => 'DESARROLLO DE NEGOCIOS',
                'responsable_id' => 1,
                'tipo_ceco' => 'CAPEX',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('cecos')->insert($cecos);
    }
}
