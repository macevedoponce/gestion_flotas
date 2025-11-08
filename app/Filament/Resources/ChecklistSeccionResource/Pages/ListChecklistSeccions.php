<?php

namespace App\Filament\Resources\ChecklistSeccionResource\Pages;

use App\Filament\Resources\ChecklistSeccionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChecklistSeccions extends ListRecords
{
    protected static string $resource = ChecklistSeccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
