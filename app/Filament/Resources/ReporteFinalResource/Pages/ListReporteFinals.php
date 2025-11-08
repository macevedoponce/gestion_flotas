<?php

namespace App\Filament\Resources\ReporteFinalResource\Pages;

use App\Filament\Resources\ReporteFinalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReporteFinals extends ListRecords
{
    protected static string $resource = ReporteFinalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
