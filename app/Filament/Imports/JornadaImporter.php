<?php

namespace App\Filament\Imports;

use App\Models\Jornada;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;

class JornadaImporter extends Importer
{
    protected static ?string $model = Jornada::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id_asignacion')->required(),
            ImportColumn::make('id_conductor')->required(),
            ImportColumn::make('dia_operativo'),
            ImportColumn::make('fecha_inicio'),
            ImportColumn::make('fecha_fin'),
            ImportColumn::make('estado'),
            ImportColumn::make('observaciones'),
        ];
    }

    public function resolveRecord(): ?Jornada
    {
        return new Jornada();
    }
}
