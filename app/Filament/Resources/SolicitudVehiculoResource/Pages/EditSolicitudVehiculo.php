<?php

namespace App\Filament\Resources\SolicitudVehiculoResource\Pages;

use App\Filament\Resources\SolicitudVehiculoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSolicitudVehiculo extends EditRecord
{
    protected static string $resource = SolicitudVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
