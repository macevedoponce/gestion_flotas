<?php

namespace App\Filament\Resources\ChecklistEjecucionResource\Pages;

use App\Filament\Resources\ChecklistEjecucionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChecklistEjecucions extends ListRecords
{
    protected static string $resource = ChecklistEjecucionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
