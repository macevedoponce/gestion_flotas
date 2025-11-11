<?php

namespace App\Filament\Resources\CecoResource\Pages;

use App\Filament\Resources\CecoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCeco extends EditRecord
{
    protected static string $resource = CecoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
