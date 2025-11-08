<?php

namespace App\Filament\Resources\TipoPreguntaResource\Pages;

use App\Filament\Resources\TipoPreguntaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoPreguntas extends ListRecords
{
    protected static string $resource = TipoPreguntaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
