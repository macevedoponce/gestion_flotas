<?php

namespace App\Filament\Exports;

use App\Models\Jornada;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;

class JornadaExporter extends Exporter
{
    protected static ?string $model = Jornada::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_jornada'),
            ExportColumn::make('id_asignacion'),
            ExportColumn::make('id_conductor'),
            ExportColumn::make('dia_operativo'),
            ExportColumn::make('fecha_inicio'),
            ExportColumn::make('fecha_fin'),
            ExportColumn::make('estado'),
            ExportColumn::make('observaciones'),
            ExportColumn::make('created_at'),
        ];
    }
}
