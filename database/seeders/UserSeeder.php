<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate([
            'email' => 'admin@demo.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('Super Admin');

        // Jefe de Proyecto
        $jefeProyecto = User::firstOrCreate([
            'email' => 'proyecto@demo.com',
        ], [
            'name' => 'Jefe Proyecto',
            'password' => Hash::make('password'),
        ]);
        $jefeProyecto->assignRole('Jefe de Proyecto');

        // Jefe de Control y Monitoreo
        $jefeControl = User::firstOrCreate([
            'email' => 'control@demo.com',
        ], [
            'name' => 'Jefe Control',
            'password' => Hash::make('password'),
        ]);
        $jefeControl->assignRole('Jefe de Control y Monitoreo');

        // Conductor
        $conductor = User::firstOrCreate([
            'email' => 'conductor@demo.com',
        ], [
            'name' => 'Conductor Prueba',
            'password' => Hash::make('password'),
        ]);
        $conductor->assignRole('Conductor');
    }
}
