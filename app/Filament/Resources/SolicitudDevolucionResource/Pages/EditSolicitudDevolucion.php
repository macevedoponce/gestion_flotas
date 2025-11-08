<?php

namespace App\Filament\Resources\SolicitudDevolucionResource\Pages;

use App\Filament\Resources\SolicitudDevolucionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSolicitudDevolucion extends EditRecord
{
    protected static string $resource = SolicitudDevolucionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
