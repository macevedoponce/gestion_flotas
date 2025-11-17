<?php

namespace App\Filament\Resources\EventoEmergenciaResource\Pages;

use App\Filament\Resources\EventoEmergenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventoEmergencias extends ListRecords
{
    protected static string $resource = EventoEmergenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
