<?php

namespace App\Filament\Imports;

use App\Models\Ceco;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class CecoImporter extends Importer
{
    protected static ?string $model = Ceco::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('codigo')
                ->label('Código')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:20'])
                ->example('0101001001'),

            ImportColumn::make('descripcion')
                ->label('Descripción')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:150'])
                ->example('Gerencia'),
        ];
    }

    /**
     * Evitar duplicados por 'codigo'
     */
    public function resolveRecord(): ?Ceco
    {
        if (Ceco::where('codigo', $this->data['codigo'])->exists()) {
            return null; // Ignorar fila si existe
        }

        return new Ceco();
    }

    public function getOptions(): array
    {
        return ['csv', 'xlsx'];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $total      = $import->total_rows;
        $successful = $import->successful_rows;
        $failed     = $import->getFailedRowsCount();
        $ignored    = $total - $successful - $failed;

        return "Importación completada. 
Procesadas: {$total}. 
Agregadas: {$successful}. 
Ignoradas (duplicadas): {$ignored}. 
Errores: {$failed}.";
    }
}
