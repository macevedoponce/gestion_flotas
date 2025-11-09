<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 🔄 Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 🔐 Crear permisos básicos
        $permisos = [
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',

            'ver roles',
            'asignar roles',

            'ver solicitudes',
            'aprobar solicitudes',

            'ver asignaciones',
            'crear asignaciones',

            'ver devoluciones',
            'validar devoluciones',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // 👔 Crear roles
        $roles = [
            'Super Admin',
            'Jefe de Proyecto',
            'Jefe de Control y Monitoreo',
            'Conductor',
        ];

        foreach ($roles as $rol) {
            Role::firstOrCreate(['name' => $rol]);
        }

        // 🚀 Dar TODOS los permisos al Super Admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions(Permission::all());
        }

        // 🎯 Asignar permisos específicos a otros roles
        Role::where('name', 'Jefe de Proyecto')->first()?->givePermissionTo([
            'ver solicitudes',
            'crear asignaciones',
            'ver devoluciones',
        ]);

        Role::where('name', 'Jefe de Control y Monitoreo')->first()?->givePermissionTo([
            'ver solicitudes',
            'aprobar solicitudes',
            'ver asignaciones',
            'crear asignaciones',
            'validar devoluciones',
        ]);

        Role::where('name', 'Conductor')->first()?->givePermissionTo([]);

        // 👤 Asignar roles a los primeros usuarios existentes
        $usuarios = User::all();

        if ($usuarios->count() >= 3) {
            $usuarios[0]->assignRole('Super Admin');
            $usuarios[1]->assignRole('Jefe de Proyecto');
            $usuarios[2]->assignRole('Jefe de Control y Monitoreo');
        } elseif ($usuarios->count() > 0) {
            $usuarios->first()->assignRole('Super Admin');
        }

        // 🧠 OPCIONAL: Gate global (Super Admin todo poderoso)
        // 👉 Activa esto en AuthServiceProvider (NO AQUÍ)
        // Gate::before(function ($user, $ability) {
        //     if ($user->hasRole('Super Admin')) {
        //         return true;
        //     }
        // });
    }
}
