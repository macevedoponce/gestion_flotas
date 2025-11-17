<?php

namespace App\Filament\Imports;

use App\Models\Vehiculo;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class VehiculoImporter extends Importer
{
    protected static ?string $model = Vehiculo::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('placa')
                ->label('Placa')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:20'])
                ->example('ABC-123'),

            ImportColumn::make('id_tipo_vehiculo')
                ->label('ID Tipo Vehículo')
                ->rules(['nullable', 'exists:tipos_vehiculo,id_tipo'])
                ->example('1'),

            ImportColumn::make('marca')->label('Marca')->example('Toyota'),
            ImportColumn::make('modelo')->label('Modelo')->example('Hilux'),
            ImportColumn::make('numero_serie')->label('N° Serie'),
            ImportColumn::make('numero_motor')->label('N° Motor'),
            ImportColumn::make('color')->label('Color'),
            ImportColumn::make('anio')->label('Año')->rules(['nullable', 'integer']),

            ImportColumn::make('tipo_combustible_id')
                ->label('ID Combustible')
                ->rules(['nullable', 'exists:tipos_combustible,id_tipo_combustible']),

            ImportColumn::make('km_actual')->label('KM')->castStateUsing(fn ($s) => $s ?: 0),

            ImportColumn::make('estado')
                ->label('Estado')
                ->castStateUsing(fn ($s) => $s ?: 'DISPONIBLE'),

            ImportColumn::make('propio')->label('Propio')->boolean()->example(true),
            ImportColumn::make('activo')->label('Activo')->boolean()->example(true),
        ];
    }

    public function resolveRecord(): ?Vehiculo
    {
        if (Vehiculo::where('placa', $this->data['placa'])->exists()) {
            return null;
        }
        return new Vehiculo();
    }

    public function getOptions(): array
    {
        return ['csv', 'xlsx'];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return "Importación finalizada. 
Procesadas: {$import->total_rows}. 
Agregadas: {$import->successful_rows}. 
Ignoradas: " . ($import->total_rows - $import->successful_rows - $import->getFailedRowsCount()) . ". 
Errores: {$import->getFailedRowsCount()}.";
    }
}
