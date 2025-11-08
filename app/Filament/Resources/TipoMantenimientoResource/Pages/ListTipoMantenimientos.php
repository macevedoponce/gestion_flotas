<?php

namespace App\Filament\Resources\TipoMantenimientoResource\Pages;

use App\Filament\Resources\TipoMantenimientoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoMantenimientos extends ListRecords
{
    protected static string $resource = TipoMantenimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
