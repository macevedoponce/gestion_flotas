<?php

namespace App\Filament\Imports;

use App\Models\Proyecto;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class ProyectoImporter extends Importer
{
    protected static ?string $model = Proyecto::class;

    public static function getColumns(): array
    {
        return [

            ImportColumn::make('codigo_anexo')
                ->label('Código Anexo')
                ->requiredMapping()
                ->rules(['required', 'string', 'size:14'])
                ->example('01010010010001'),

            ImportColumn::make('descripcion')
                ->label('Descripción')
                ->requiredMapping()
                ->rules(['required', 'string'])
                ->example('Proyecto de obra civil'),

            ImportColumn::make('responsable_id')
                ->label('ID Responsable')
                ->rules(['nullable', 'exists:users,id'])
                ->example('1'),

            ImportColumn::make('id_ceco')
                ->label('ID CECO')
                ->rules(['nullable', 'exists:cecos,id_ceco'])
                ->example('1'),

            ImportColumn::make('lugar_trabajo')
                ->label('Lugar de Trabajo')
                ->rules(['nullable', 'string'])
                ->example('Sede Central'),

            ImportColumn::make('fecha_inicio')
                ->label('Fecha Inicio')
                ->rules(['nullable', 'date'])
                ->example('2025-01-01'),

            ImportColumn::make('fecha_fin')
                ->label('Fecha Fin')
                ->rules(['nullable', 'date'])
                ->example('2025-12-31'),

            ImportColumn::make('estado')
                ->label('Estado')
                ->rules(['nullable', 'in:ACTIVO,INACTIVO,CERRADO'])
                ->castStateUsing(fn ($state) => $state ?: 'ACTIVO')
                ->example('ACTIVO'),
        ];
    }

    public function resolveRecord(): ?Proyecto
    {
        if (Proyecto::where('codigo_anexo', $this->data['codigo_anexo'])->exists()) {
            return null; // Ignorar fila duplicada
        }

        return new Proyecto();
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

        return "Importación completada. Procesadas: {$total}. Agregadas: {$successful}. Ignoradas: {$ignored}. Errores: {$failed}.";
    }
}
