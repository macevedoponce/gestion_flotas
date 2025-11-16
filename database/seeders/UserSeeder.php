<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Super Administrador',
                'password' => bcrypt('admin123')
            ]
        );
        $admin->assignRole('Super Admin');

        $jefeProyecto = User::firstOrCreate(
            ['email' => 'jefeproyecto@test.com'],
            [
                'name' => 'Jefe de Proyecto Demo',
                'password' => bcrypt('demo123')
            ]
        );
        $jefeProyecto->assignRole('Jefe de Proyecto');

        $jefeControl = User::firstOrCreate(
            ['email' => 'control@test.com'],
            [
                'name' => 'Jefe Control Demo',
                'password' => bcrypt('demo123')
            ]
        );
        $jefeControl->assignRole('Jefe de Control y Monitoreo');
    }
}
