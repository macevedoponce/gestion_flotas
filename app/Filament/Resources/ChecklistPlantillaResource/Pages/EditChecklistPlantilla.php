<?php

namespace App\Filament\Resources\ChecklistPlantillaResource\Pages;

use App\Filament\Resources\ChecklistPlantillaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChecklistPlantilla extends EditRecord
{
    protected static string $resource = ChecklistPlantillaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
