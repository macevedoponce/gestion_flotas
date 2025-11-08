<?php

namespace App\Filament\Resources\TipoPreguntaResource\Pages;

use App\Filament\Resources\TipoPreguntaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoPregunta extends EditRecord
{
    protected static string $resource = TipoPreguntaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
