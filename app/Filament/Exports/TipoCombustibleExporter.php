<?php

namespace App\Filament\Exports;

use App\Models\TipoCombustible;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class TipoCombustibleExporter extends Exporter
{
    protected static ?string $model = TipoCombustible::class;

    /**
     * Columnas exportables, con estilo idéntico a TipoVehiculoExporter.
     */
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_tipo_combustible')
                ->label('ID'),

            ExportColumn::make('nombre')
                ->label('Nombre'),

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
        return 'TipoCombustible';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'La exportación de Tipos de Combustible se completó correctamente y está lista para descargar.';
    }
}
