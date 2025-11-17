<?php

namespace App\Filament\Resources\ChecklistEjecucionResource\Pages;

use App\Filament\Resources\ChecklistEjecucionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChecklistEjecucion extends EditRecord
{
    protected static string $resource = ChecklistEjecucionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
