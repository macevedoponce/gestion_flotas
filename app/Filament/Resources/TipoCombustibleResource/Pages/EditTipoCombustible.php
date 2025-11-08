<?php

namespace App\Filament\Resources\TipoCombustibleResource\Pages;

use App\Filament\Resources\TipoCombustibleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoCombustible extends EditRecord
{
    protected static string $resource = TipoCombustibleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
