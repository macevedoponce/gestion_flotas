<?php

namespace App\Filament\Imports;

use App\Models\SolicitudDevolucion;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class SolicitudDevolucionImporter extends Importer
{
    protected static ?string $model = SolicitudDevolucion::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id_asignacion')
                ->label('ID Asignación')
                ->rules(['nullable', 'exists:asignaciones_vehiculos,id_asignacion'])
                ->example(5),

            ImportColumn::make('id_usuario_solicitante')
                ->label('ID Solicitante')
                ->rules(['nullable', 'exists:users,id'])
                ->example(2),

            ImportColumn::make('fecha_solicitud')
                ->label('Fecha Solicitud')
                ->rules(['nullable', 'date'])
                ->castStateUsing(fn ($v) => $v ?: now())
                ->example('2025-01-15 10:00:00'),

            ImportColumn::make('observaciones')
                ->label('Observaciones')
                ->rules(['nullable', 'string'])
                ->example('Vehículo entregado con tanque medio.'),

            ImportColumn::make('estado')
                ->label('Estado')
                ->rules(['nullable', 'in:PENDIENTE,APROBADA,RECHAZADA,COMPLETADA'])
                ->castStateUsing(fn ($v) => $v ?: 'PENDIENTE')
                ->example('PENDIENTE'),
        ];
    }

    /**
     * Evitar duplicados por combinación:
     *  - id_asignacion
     *  - fecha_solicitud (día)
     */
    public function resolveRecord(): ?SolicitudDevolucion
    {
        $idAsignacion = $this->data['id_asignacion'] ?? null;
        $fecha        = $this->data['fecha_solicitud'] ?? null;

        if ($idAsignacion && $fecha) {
            $exists = SolicitudDevolucion::where('id_asignacion', $idAsignacion)
                ->whereDate('fecha_solicitud', date('Y-m-d', strtotime($fecha)))
                ->exists();

            if ($exists) {
                return null; // fila duplicada
            }
        }

        return new SolicitudDevolucion();
    }

    public function getOptions(): array
    {
        return ['csv', 'xlsx'];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return sprintf(
            "Importación completa. Procesadas: %d. Agregadas: %d. Ignoradas: %d. Errores: %d.",
            $total = $import->total_rows,
            $success = $import->successful_rows,
            $ignored = $total - $success - $import->getFailedRowsCount(),
            $import->getFailedRowsCount()
        );
    }
}
