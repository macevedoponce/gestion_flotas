<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Principal',
                'email' => 'admin@empresa.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'telefono' => '999111222',
                'id_rol' => 1, // Administrador
                'activo' => true,
            ],
            [
                'name' => 'Supervisor Flota',
                'email' => 'supervisor@empresa.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'telefono' => '999222333',
                'id_rol' => 3, // Supervisor
                'activo' => true,
            ],
            [
                'name' => 'Juan Solicitante',
                'email' => 'usuario@empresa.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'telefono' => '999333444',
                'id_rol' => 4, // Usuario
                'activo' => true,
            ],
            [
                'name' => 'Maria Coordinadora',
                'email' => 'coordinador@empresa.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'telefono' => '999444555',
                'id_rol' => 3, // Supervisor
                'activo' => true,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}