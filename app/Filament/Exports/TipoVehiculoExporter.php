<?php

namespace App\Filament\Exports;

use App\Models\TipoVehiculo;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class TipoVehiculoExporter extends Exporter
{
    protected static ?string $model = TipoVehiculo::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_tipo')->label('ID'),
            ExportColumn::make('nombre')->label('Nombre'),
            ExportColumn::make('descripcion')->label('Descripción'),
            ExportColumn::make('capacidad_personas')->label('Capacidad Personas'),
            ExportColumn::make('capacidad_tanque')->label('Capacidad Tanque (L)'),            

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
        return 'TiposVehiculo';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'La exportación de Tipos de Vehículos se completó correctamente y está lista para descargar.';
    }
}
