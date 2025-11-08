<?php

namespace App\Filament\Resources\ChecklistRespuestaResource\Pages;

use App\Filament\Resources\ChecklistRespuestaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChecklistRespuestas extends ListRecords
{
    protected static string $resource = ChecklistRespuestaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
