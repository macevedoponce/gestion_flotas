<?php

namespace App\Filament\Resources\TipoEventoEmergenciaResource\Pages;

use App\Filament\Resources\TipoEventoEmergenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoEventoEmergencias extends ListRecords
{
    protected static string $resource = TipoEventoEmergenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
