<?php

namespace App\Filament\Resources\EventoEmergenciaResource\Pages;

use App\Filament\Resources\EventoEmergenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventoEmergencia extends EditRecord
{
    protected static string $resource = EventoEmergenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
