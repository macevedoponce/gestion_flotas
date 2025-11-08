<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposCombustibleTableSeeder extends Seeder
{
    public function run(): void
    {
        $combustibles = [
            ['nombre' => 'Gasolina 84'],
            ['nombre' => 'Gasolina 90'],
            ['nombre' => 'Gasolina 95'],
            ['nombre' => 'Gasolina 97'],
            ['nombre' => 'Diésel B5'],
            ['nombre' => 'Diésel B10'],
            ['nombre' => 'GLP'],
            ['nombre' => 'Gas Natural'],
        ];

        foreach ($combustibles as $combustible) {
            DB::table('tipos_combustible')->insert($combustible);
        }
    }
}