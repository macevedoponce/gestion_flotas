<?php

namespace App\Filament\Imports;

use App\Models\Conductor;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class ConductorImporter extends Importer
{
    protected static ?string $model = Conductor::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre_completo')
                ->label('Nombre Completo')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:120'])
                ->example('Carlos Rojas'),

            ImportColumn::make('documento_identidad')
                ->label('DNI')
                ->requiredMapping()
                ->rules(['required', 'digits:8'])
                ->example('45678901'),

            ImportColumn::make('celular')
                ->label('Celular')
                ->rules(['nullable', 'digits:9'])
                ->example('987654321'),

            ImportColumn::make('licencia_numero')
                ->label('N° Licencia')
                ->requiredMapping()
                ->rules(['required', 'string']),

            ImportColumn::make('licencia_categoria')
                ->label('Categoría')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:10']),

            ImportColumn::make('licencia_vencimiento')
                ->label('Vencimiento')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->example('2026-05-10'),

            ImportColumn::make('estado_disponibilidad')
                ->label('Disponibilidad')
                ->rules(['nullable', 'in:DISPONIBLE,OCUPADO,INACTIVO'])
                ->castStateUsing(fn ($state) => $state ?: 'DISPONIBLE')
                ->example('DISPONIBLE'),

            ImportColumn::make('activo')
                ->label('Activo')
                ->boolean()
                ->rules(['nullable'])
                ->castStateUsing(function ($state) {
                    // Si viene vacío o null → true
                    if ($state === null || $state === '') {
                        return true;
                    }

                    // Aceptar 1/0, true/false, sí/no, etc. si lo necesitas
                    return filter_var($state, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? true;
                })
                ->example(true),
        ];
    }

    /**
     * Ignorar duplicados por documento_identidad (DNI).
     */
    public function resolveRecord(): ?Conductor
    {
        if (Conductor::where('documento_identidad', $this->data['documento_identidad'])->exists()) {
            return null; // Ignorar fila sin marcar error
        }

        return new Conductor();
    }

    /**
     * Extensiones aceptadas.
     */
    public function getOptions(): array
    {
        return ['csv', 'xlsx'];
    }

    /**
     * Mensaje final similar a tu TipoVehiculoImporter.
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
            $lines[] = "Filas ignoradas por duplicado (DNI existente): {$ignored}.";
        }

        if ($failed > 0) {
            $lines[] = "Filas con errores: {$failed}. Puede descargar el archivo de errores para revisarlas.";
        }

        return implode(" ", $lines);
    }
}
