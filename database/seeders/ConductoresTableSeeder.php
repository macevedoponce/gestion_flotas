<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ConductoresTableSeeder extends Seeder
{
    public function run(): void
    {
        $conductores = [
            [
                'nombre_completo' => 'Juan Pérez Rodríguez',
                'documento_identidad' => '12345678',
                'celular' => '999888777',
                'licencia_numero' => 'A12345678',
                'licencia_categoria' => 'A-IIIa',
                'licencia_vencimiento' => '2025-12-31',
                'username_app' => 'juan.perez',
                'password_hash' => Hash::make('password123'),
                'estado_disponibilidad' => 'DISPONIBLE',
                'activo' => true,
            ],
            [
                'nombre_completo' => 'María García López',
                'documento_identidad' => '87654321',
                'celular' => '999777666',
                'licencia_numero' => 'B87654321',
                'licencia_categoria' => 'A-IIb',
                'licencia_vencimiento' => '2026-06-30',
                'username_app' => 'maria.garcia',
                'password_hash' => Hash::make('password123'),
                'estado_disponibilidad' => 'DISPONIBLE',
                'activo' => true,
            ],
            [
                'nombre_completo' => 'Carlos Silva Mendoza',
                'documento_identidad' => '11223344',
                'celular' => '999666555',
                'licencia_numero' => 'C11223344',
                'licencia_categoria' => 'A-I',
                'licencia_vencimiento' => '2024-09-15',
                'username_app' => 'carlos.silva',
                'password_hash' => Hash::make('password123'),
                'estado_disponibilidad' => 'NO_DISPONIBLE',
                'activo' => true,
            ],
        ];

        foreach ($conductores as $conductor) {
            DB::table('conductores')->updateOrInsert(
                ['documento_identidad' => $conductor['documento_identidad']],
                $conductor
            );
        }
    }
}
