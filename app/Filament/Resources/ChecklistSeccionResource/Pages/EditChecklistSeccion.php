<?php

namespace App\Filament\Resources\ChecklistSeccionResource\Pages;

use App\Filament\Resources\ChecklistSeccionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChecklistSeccion extends EditRecord
{
    protected static string $resource = ChecklistSeccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
