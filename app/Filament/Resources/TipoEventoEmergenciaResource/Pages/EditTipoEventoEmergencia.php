<?php

namespace App\Filament\Resources\TipoEventoEmergenciaResource\Pages;

use App\Filament\Resources\TipoEventoEmergenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoEventoEmergencia extends EditRecord
{
    protected static string $resource = TipoEventoEmergenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
