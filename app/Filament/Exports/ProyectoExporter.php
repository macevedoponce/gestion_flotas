<?php

namespace App\Filament\Exports;

use App\Models\Proyecto;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class ProyectoExporter extends Exporter
{
    protected static ?string $model = Proyecto::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_proyecto')->label('ID'),
            ExportColumn::make('codigo_anexo')->label('Código'),
            ExportColumn::make('descripcion')->label('Descripción'),
            ExportColumn::make('responsable.name')->label('Responsable'),
            ExportColumn::make('ceco.codigo')->label('CECO'),
            ExportColumn::make('lugar_trabajo')->label('Lugar'),
            ExportColumn::make('fecha_inicio')->label('Fecha Inicio'),
            ExportColumn::make('fecha_fin')->label('Fecha Fin'),
            ExportColumn::make('estado')->label('Estado'),

            ExportColumn::make('created_at')
                ->label('Creado')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state->format('d/m/Y H:i') : ''
                ),
        ];
    }

    public static function getSheetName(): string
    {
        return 'Proyectos';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return "La exportación de Proyectos fue exitosa.";
    }
}
