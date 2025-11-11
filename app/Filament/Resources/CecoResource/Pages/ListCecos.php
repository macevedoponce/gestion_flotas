<?php

namespace App\Filament\Resources\CecoResource\Pages;

use App\Filament\Resources\CecoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCecos extends ListRecords
{
    protected static string $resource = CecoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
