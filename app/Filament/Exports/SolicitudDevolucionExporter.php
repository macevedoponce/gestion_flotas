<?php

namespace App\Filament\Exports;

use App\Models\SolicitudDevolucion;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class SolicitudDevolucionExporter extends Exporter
{
    protected static ?string $model = SolicitudDevolucion::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_devolucion')->label('ID'),
            ExportColumn::make('id_asignacion')->label('Asignación'),
            ExportColumn::make('usuarioSolicitante.name')->label('Solicitante'),
            ExportColumn::make('estado')->label('Estado'),

            ExportColumn::make('fecha_solicitud')
                ->label('Fecha Solicitud')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state->format('d/m/Y H:i') : ''
                ),

            ExportColumn::make('observaciones')->label('Observaciones'),

            ExportColumn::make('created_at')
                ->label('Creado')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state->format('d/m/Y H:i') : ''
                ),
        ];
    }

    public static function getSheetName(): string
    {
        return 'Devoluciones';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return "Exportación de devoluciones completada.";
    }
}
