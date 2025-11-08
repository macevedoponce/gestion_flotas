<?php

namespace App\Filament\Resources\ChecklistPlantillaResource\Pages;

use App\Filament\Resources\ChecklistPlantillaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChecklistPlantillas extends ListRecords
{
    protected static string $resource = ChecklistPlantillaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
