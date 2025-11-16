<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CecosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cecos')->insert([
            ['codigo' => '0101001001', 'descripcion' => 'GERENCIA'],
            ['codigo' => '0101001002', 'descripcion' => 'ADMINISTRACIÓN'],
        ]);
    }
}

