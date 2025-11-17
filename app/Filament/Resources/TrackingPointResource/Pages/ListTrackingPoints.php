<?php

namespace App\Filament\Resources\TrackingPointResource\Pages;

use App\Filament\Resources\TrackingPointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrackingPoints extends ListRecords
{
    protected static string $resource = TrackingPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
