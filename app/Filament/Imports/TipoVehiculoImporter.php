<?php

namespace App\Filament\Imports;

use App\Models\TipoVehiculo;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TipoVehiculoImporter extends Importer
{
    protected static ?string $model = TipoVehiculo::class;

    /**
     * Columnas que se pueden importar.
     */
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre')
                ->label('Nombre')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:80'])
                ->example('Camioneta 4x4'),

            ImportColumn::make('descripcion')
                ->label('Descripción')
                ->rules(['nullable', 'string'])
                ->example('Vehículo todoterreno'),

            ImportColumn::make('capacidad_personas')
                ->label('Capacidad Personas')
                ->integer()
                ->rules(['required', 'integer', 'min:1'])
                ->example(5),

            ImportColumn::make('capacidad_tanque')
                ->label('Capacidad Tanque (L)')
                ->numeric()
                ->rules(['required', 'numeric', 'min:0'])
                ->example(65),
        ];
    }

    /**
     * Lógica para evitar duplicados:
     * Si el nombre YA existe, se IGNORA la fila.
     */
    public function resolveRecord(): ?TipoVehiculo
    {
        $exists = TipoVehiculo::where('nombre', $this->data['nombre'])->exists();

        // Si existe, ignoramos la fila sin marcarla como error.
        if ($exists) {
            return null;
        }

        return new TipoVehiculo();
    }

    /**
     * Soporte para CSV y XLSX.
     */
    public function getOptions(): array
    {
        return ['csv', 'xlsx'];
    }

    /**
     * Mensaje final totalmente detallado.
     */
    public static function getCompletedNotificationBody(Import $import): string
    {
        $total        = $import->total_rows;
        $successful   = $import->successful_rows;
        $failed       = $import->getFailedRowsCount();
        $ignored      = $total - $successful - $failed;

        $lines = [];
        $lines[] = "Importación completada.";
        $lines[] = "Filas procesadas: {$total}.";
        $lines[] = "Filas agregadas correctamente: {$successful}.";

        if ($ignored > 0) {
            $lines[] = "Filas ignoradas porque ya existían: {$ignored}.";
        }

        if ($failed > 0) {
            $lines[] = "Filas con errores: {$failed}. Puede descargar el archivo de errores para revisarlas.";
        }

        return implode(" ", $lines);
    }
}
