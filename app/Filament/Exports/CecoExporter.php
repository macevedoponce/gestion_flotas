<?php

namespace App\Filament\Exports;

use App\Models\Ceco;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class CecoExporter extends Exporter
{
    protected static ?string $model = Ceco::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_ceco')->label('ID'),
            ExportColumn::make('codigo')->label('Código'),
            ExportColumn::make('descripcion')->label('Descripción'),

            ExportColumn::make('created_at')
                ->label('Creado')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state->format('d/m/Y H:i') : ''
                ),
        ];
    }

    public static function getSheetName(): string
    {
        return 'CECOs';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'La exportación de CECOs se completó correctamente.';
    }
}
