<?php

namespace App\Filament\Imports;

use App\Models\TipoCombustible;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TipoCombustibleImporter extends Importer
{
    protected static ?string $model = TipoCombustible::class;

    /**
     * Columnas importables para Tipos de Combustible.
     * Estructura y estilo idéntico a TipoVehiculoImporter.
     */
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre')
                ->label('Nombre del Combustible')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:80'])
                ->example('Diesel'),
        ];
    }

    /**
     * Evitar duplicados.
     * Si el nombre YA existe, la fila se ignora.
     */
    public function resolveRecord(): ?TipoCombustible
    {
        $exists = TipoCombustible::where('nombre', $this->data['nombre'])->exists();

        if ($exists) {
            return null; // Ignorar fila, sin marcar error
        }

        return new TipoCombustible();
    }

    /**
     * Soporte para CSV y XLSX.
     */
    public function getOptions(): array
    {
        return ['csv', 'xlsx'];
    }

    /**
     * Mensaje final idéntico en estilo al de TipoVehiculoImporter.
     */
    public static function getCompletedNotificationBody(Import $import): string
    {
        $total      = $import->total_rows;
        $successful = $import->successful_rows;
        $failed     = $import->getFailedRowsCount();
        $ignored    = $total - $successful - $failed;

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
