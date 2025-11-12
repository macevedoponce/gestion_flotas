<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AsignacionVehiculo;
use App\Models\SolicitudVehiculo;
use App\Models\Vehiculo;
use App\Models\Conductor;
use App\Models\User;
use App\Models\Proyecto;

class AsignacionesVehiculosTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener la primera solicitud de vehículo existente
        $solicitud = SolicitudVehiculo::first();

        // Buscar o crear al Jefe de Control y Monitoreo
        $jefeControl = User::whereHas('roles', fn($q) => $q->where('name', 'Jefe de Control y Monitoreo'))->first();
        if (!$jefeControl) {
            $jefeControl = User::factory()->create([
                'name' => 'Jefe Control Temporal',
                'email' => 'control@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Verificar que exista una solicitud
        if (!$solicitud) {
            $this->command->warn('⚠️ No se encontró ninguna solicitud de vehículo. Ejecuta primero el seeder SolicitudVehiculoTableSeeder.');
            return;
        }

        // Obtener el proyecto vinculado a la solicitud
        $proyecto = Proyecto::find($solicitud->id_proyecto);
        if (!$proyecto) {
            $proyecto = Proyecto::create([
                'ceco_id' => 1,
                'anexo' => 'PROY-' . now()->year . '-AUTO',
                'anexo_descripcion' => 'Proyecto generado automáticamente desde solicitud ' . $solicitud->id_solicitud,
                'encargado_id' => $jefeControl->id,
                'region' => $solicitud->lugar_trabajo ?? 'Desconocida',
                'unidad_negocio' => 'Automático',
                'tipo_flujo' => 'OPEX',
                'proyecto' => 'Proyecto auto generado',
                'fecha_inicio' => $solicitud->fecha_inicio ?? now(),
                'fecha_fin' => $solicitud->fecha_fin ?? now()->addDays(10),
                'estado' => 'ACTIVO',
            ]);
        }

        // Obtener vehículo y conductor disponibles
        $vehiculo = Vehiculo::first();
        $conductor = Conductor::where('estado_disponibilidad', 'DISPONIBLE')->first();

        // Crear la asignación de vehículo
        AsignacionVehiculo::create([
            'id_solicitud' => $solicitud->id_solicitud,
            'id_proyecto' => $proyecto->id_proyecto,
            'id_vehiculo' => $vehiculo->id_vehiculo ?? 1,
            'id_conductor' => $conductor->id_conductor ?? null,
            'id_jefe_control' => $jefeControl->id,
            'fecha_asignacion' => now(),
            'fecha_finalizacion' => $solicitud->fecha_fin ?? now()->addDays(5),
            'estado' => 'ACTIVA',
            'observaciones' => 'Asignación creada automáticamente desde seeder para pruebas.',
        ]);
    }
}
