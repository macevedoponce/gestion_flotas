<?php

namespace App\Filament\Resources\AsignacionVehiculoResource\Pages;

use App\Filament\Resources\AsignacionVehiculoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsignacionVehiculo extends EditRecord
{
    protected static string $resource = AsignacionVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
