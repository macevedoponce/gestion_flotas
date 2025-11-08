<?php

namespace App\Filament\Resources\ChecklistRespuestaResource\Pages;

use App\Filament\Resources\ChecklistRespuestaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChecklistRespuesta extends EditRecord
{
    protected static string $resource = ChecklistRespuestaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
