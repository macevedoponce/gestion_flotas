<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Roles base
        $roles = [
            'Super Admin',
            'Jefe de Proyecto',
            'Jefe de Control y Monitoreo',
            'Conductor',
        ];

        foreach ($roles as $rol) {
            Role::firstOrCreate(['name' => $rol]);
        }

        // Permisos básicos
        $permisos = [
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios',
            'ver solicitudes', 'crear solicitudes', 'asignar vehículos', 'validar devoluciones'
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Asignar todos los permisos al Super Admin
        Role::findByName('Super Admin')->givePermissionTo(Permission::all());
    }
}
