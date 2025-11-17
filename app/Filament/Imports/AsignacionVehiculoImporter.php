<?php

namespace App\Filament\Imports;

use App\Models\AsignacionVehiculo;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class AsignacionVehiculoImporter extends Importer
{
    protected static ?string $model = AsignacionVehiculo::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id_solicitud')
                ->label('ID Solicitud')
                ->rules(['nullable', 'exists:solicitud_vehiculo,id_solicitud'])
                ->example(10),

            ImportColumn::make('id_proyecto')
                ->label('ID Proyecto')
                ->rules(['nullable', 'exists:proyectos,id_proyecto'])
                ->example(5),

            ImportColumn::make('id_vehiculo')
                ->label('ID Vehículo')
                ->requiredMapping()
                ->rules(['required', 'exists:vehiculos,id_vehiculo'])
                ->example(3),

            ImportColumn::make('id_conductor')
                ->label('ID Conductor')
                ->requiredMapping()
                ->rules(['required', 'exists:conductores,id_conductor'])
                ->example(7),

            ImportColumn::make('id_jefe_control')
                ->label('ID Jefe Control')
                ->rules(['nullable', 'exists:users,id'])
                ->example(1),

            ImportColumn::make('fecha_asignacion')
                ->label('Fecha Asignación')
                ->rules(['nullable', 'date'])
                ->castStateUsing(function ($state) {
                    // Si no viene en el archivo, usamos now() para mantener coherencia
                    return $state ?: now();
                })
                ->example('2025-11-16 08:00:00'),

            ImportColumn::make('fecha_finalizacion')
                ->label('Fecha Finalización')
                ->rules(['nullable', 'date'])
                ->example('2025-11-16 18:00:00'),

            ImportColumn::make('estado')
                ->label('Estado')
                ->rules(['nullable', 'in:PENDIENTE,ACTIVA,FINALIZADA,ANULADA'])
                ->castStateUsing(fn ($state) => $state ?: 'PENDIENTE')
                ->example('ACTIVA'),

            ImportColumn::make('observaciones_recepcion')
                ->label('Obs. Recepción')
                ->rules(['nullable', 'string'])
                ->example('Vehículo entregado en buen estado.'),

            ImportColumn::make('observaciones')
                ->label('Observaciones')
                ->rules(['nullable', 'string'])
                ->example('Uso para supervisión de obra.'),
        ];
    }

    /**
     * Evita duplicados:
     * Consideramos duplicada una asignación con misma combinación:
     *  - id_vehiculo
     *  - id_conductor
     *  - fecha_asignacion (día)
     */
    public function resolveRecord(): ?AsignacionVehiculo
    {
        $vehiculoId = $this->data['id_vehiculo'] ?? null;
        $conductorId = $this->data['id_conductor'] ?? null;
        $fecha = $this->data['fecha_asignacion'] ?? null;

        if ($vehiculoId && $conductorId && $fecha) {
            $exists = AsignacionVehiculo::where('id_vehiculo', $vehiculoId)
                ->where('id_conductor', $conductorId)
                ->whereDate('fecha_asignacion', date('Y-m-d', strtotime($fecha)))
                ->exists();

            if ($exists) {
                return null; // Ignorar fila duplicada
            }
        }

        return new AsignacionVehiculo();
    }

    /**
     * Tipos de archivo soportados.
     */
    public function getOptions(): array
    {
        return ['csv', 'xlsx'];
    }

    /**
     * Mensaje final estilo tus otros importers.
     */
    public static function getCompletedNotificationBody(Import $import): string
    {
        $total      = $import->total_rows;
        $successful = $import->successful_rows;
        $failed     = $import->getFailedRowsCount();
        $ignored    = $total - $successful - $failed;

        $msg = [];
        $msg[] = "Importación de Asignaciones completada.";
        $msg[] = "Filas procesadas: {$total}.";
        $msg[] = "Filas agregadas: {$successful}.";

        if ($ignored > 0) {
            $msg[] = "Filas ignoradas (posibles duplicados): {$ignored}.";
        }

        if ($failed > 0) {
            $msg[] = "Filas con error: {$failed}. Revise el archivo de errores.";
        }

        return implode(' ', $msg);
    }
}
