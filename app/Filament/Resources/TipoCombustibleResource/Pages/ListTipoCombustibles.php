<?php

namespace App\Filament\Resources\TipoCombustibleResource\Pages;

use App\Filament\Resources\TipoCombustibleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoCombustibles extends ListRecords
{
    protected static string $resource = TipoCombustibleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
