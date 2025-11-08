<?php

namespace App\Filament\Resources\SolicitudVehiculoResource\Pages;

use App\Filament\Resources\SolicitudVehiculoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSolicitudVehiculos extends ListRecords
{
    protected static string $resource = SolicitudVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
