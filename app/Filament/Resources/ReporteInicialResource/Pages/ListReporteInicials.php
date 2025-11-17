<?php

namespace App\Filament\Resources\ReporteInicialResource\Pages;

use App\Filament\Resources\ReporteInicialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReporteInicials extends ListRecords
{
    protected static string $resource = ReporteInicialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
