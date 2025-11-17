<?php

namespace App\Filament\Exports;

use App\Models\AsignacionVehiculo;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class AsignacionVehiculoExporter extends Exporter
{
    protected static ?string $model = AsignacionVehiculo::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_asignacion')->label('ID'),
            ExportColumn::make('vehiculo.placa')->label('Placa'),
            ExportColumn::make('conductor.nombre_completo')->label('Conductor'),
            ExportColumn::make('proyecto.codigo_anexo')->label('Proyecto'),
            ExportColumn::make('estado')->label('Estado'),

            ExportColumn::make('fecha_asignacion')
                ->label('Asignado')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state->format('d/m/Y H:i') : ''
                ),

            ExportColumn::make('fecha_finalizacion')
                ->label('Finalizado')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state->format('d/m/Y H:i') : ''
                ),

            ExportColumn::make('created_at')
                ->label('Creado')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state->format('d/m/Y H:i') : ''
                ),
        ];
    }

    public static function getSheetName(): string
    {
        return 'Asignaciones';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return "Exportación completada.";
    }
}
