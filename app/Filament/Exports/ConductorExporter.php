<?php

namespace App\Filament\Exports;

use App\Models\Conductor;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class ConductorExporter extends Exporter
{
    protected static ?string $model = Conductor::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_conductor')->label('ID'),

            ExportColumn::make('nombre_completo')->label('Nombre'),

            ExportColumn::make('documento_identidad')->label('DNI'),

            ExportColumn::make('celular')->label('Celular'),

            ExportColumn::make('licencia_numero')->label('Licencia'),

            ExportColumn::make('licencia_categoria')->label('Categoría'),

            ExportColumn::make('licencia_vencimiento')
                ->label('Vence')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state->format('d/m/Y') : ''
                ),

            ExportColumn::make('estado_disponibilidad')->label('Estado'),

            ExportColumn::make('activo')->label('Activo'),

            ExportColumn::make('created_at')
                ->label('Creado')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state->format('d/m/Y H:i') : ''
                ),

            ExportColumn::make('updated_at')
                ->label('Actualizado')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state->format('d/m/Y H:i') : ''
                ),
        ];
    }

    public static function getSheetName(): string
    {
        return 'Conductores';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'La exportación de Conductores se completó exitosamente.';
    }
}
