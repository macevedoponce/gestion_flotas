<?php

namespace App\Filament\Resources\ChecklistTipoPreguntaResource\Pages;

use App\Filament\Resources\ChecklistTipoPreguntaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChecklistTipoPreguntas extends ListRecords
{
    protected static string $resource = ChecklistTipoPreguntaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
