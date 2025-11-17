<?php

namespace App\Filament\Exports;

use App\Models\Vehiculo;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class VehiculoExporter extends Exporter
{
    protected static ?string $model = Vehiculo::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_vehiculo')->label('ID'),
            ExportColumn::make('placa')->label('Placa'),
            ExportColumn::make('tipoVehiculo.nombre')->label('Tipo'),
            ExportColumn::make('marca')->label('Marca'),
            ExportColumn::make('modelo')->label('Modelo'),
            ExportColumn::make('anio')->label('Año'),
            ExportColumn::make('km_actual')->label('KM'),
            ExportColumn::make('estado')->label('Estado'),

            ExportColumn::make('vencimiento_soat')
                ->label('SOAT Vence')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : ''),

            ExportColumn::make('vencimiento_citv')
                ->label('CITV Vence')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y') : ''),

            ExportColumn::make('propio')->label('Propio'),
            ExportColumn::make('activo')->label('Activo'),

            ExportColumn::make('created_at')
                ->label('Creado')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : ''),
        ];
    }

    public static function getSheetName(): string
    {
        return 'Vehiculos';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return "La exportación de vehículos fue exitosa.";
    }
}
