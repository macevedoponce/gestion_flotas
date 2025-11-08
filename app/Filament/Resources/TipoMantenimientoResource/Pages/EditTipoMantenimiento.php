<?php

namespace App\Filament\Resources\TipoMantenimientoResource\Pages;

use App\Filament\Resources\TipoMantenimientoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoMantenimiento extends EditRecord
{
    protected static string $resource = TipoMantenimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
