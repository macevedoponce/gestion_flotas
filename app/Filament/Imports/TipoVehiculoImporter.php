<?php

namespace App\Filament\Imports;

use App\Models\TipoVehiculo;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TipoVehiculoImporter extends Importer
{
    protected static ?string $model = TipoVehiculo::class;

    public static function getColumns(): array
    {
        return [

            ImportColumn::make('nombre')
                ->label('Nombre')
                ->required()
                ->rules(['string', 'max:80'])
                ->example('Camioneta 4x4'),

            ImportColumn::make('descripcion')
                ->label('Descripción')
                ->nullable()
                ->rules(['string'])
                ->example('Vehículo robusto para campo'),

            ImportColumn::make('capacidad_personas')
                ->label('Capacidad Personas')
                ->required()
                ->rules(['integer', 'min:1'])
                ->example('5'),

            ImportColumn::make('capacidad_tanque')
                ->label('Capacidad Tanque (L)')
                ->required()
                ->rules(['numeric', 'min:0'])
                ->example('65'),
        ];
    }

    public function resolveRecord(): ?TipoVehiculo
    {
        // Buscar por nombre para evitar duplicados
        return TipoVehiculo::firstOrNew([
            'nombre' => $this->data['nombre'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Importación completada: ';

        $body .= "{$import->successful_rows} filas correctas";

        if ($import->failed_rows > 0) {
            $body .= ", {$import->failed_rows} filas con errores.";
        }

        return $body;
    }
}
