<?php

namespace App\Filament\Resources\AbastecimientoResource\Pages;

use App\Filament\Resources\AbastecimientoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAbastecimiento extends EditRecord
{
    protected static string $resource = AbastecimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
