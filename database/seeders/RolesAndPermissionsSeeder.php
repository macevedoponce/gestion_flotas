<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // limpiar cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permisos = [
            'view solicitudes',
            'create solicitudes',
            'update solicitudes',
            'delete solicitudes',

            'view asignaciones',
            'create asignaciones',
            'update asignaciones',
            'delete asignaciones',

            'view devoluciones',
            'create devoluciones',
            'update devoluciones',
            'delete devoluciones',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Crear roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $jefeProyecto = Role::firstOrCreate(['name' => 'Jefe de Proyecto']);
        $jefeControl = Role::firstOrCreate(['name' => 'Jefe de Control y Monitoreo']);
        $asistenteControl = Role::firstOrCreate(['name' => 'Asistente de Control y Monitoreo']);

        // Asignar permisos por rol
        $superAdmin->givePermissionTo(Permission::all());

        $jefeProyecto->givePermissionTo([
            'view solicitudes',
            'create solicitudes',
            'update solicitudes',
            'delete solicitudes',
            'view devoluciones',
            'create devoluciones',
        ]);

        $jefeControl->givePermissionTo([
            'view solicitudes',
            'create solicitudes',
            'update solicitudes',
            'view asignaciones',
            'create asignaciones',
            'update asignaciones',
            'view devoluciones',
            'update devoluciones',
        ]);

        $asistenteControl->givePermissionTo([
            'view solicitudes',
            'view asignaciones',
            'view devoluciones',
            'update devoluciones',
        ]);
    }
}
