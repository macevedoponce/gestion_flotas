<?php

namespace App\Filament\Resources\ChecklistEjecucionResource\Pages;

use App\Filament\Resources\ChecklistEjecucionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Grid;

class ViewChecklistEjecucion extends ViewRecord
{
    protected static string $resource = ChecklistEjecucionResource::class;

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    public function getHeading(): string
    {
        return "Detalle del Checklist";
    }

    protected function getFormSchema(): array
    {
        $record = $this->record;

        $secciones = $record
            ->plantilla
            ->secciones()
            ->orderBy('orden')
            ->with('items')
            ->get();

        $schema = [];

        foreach ($secciones as $seccion) {
            $schema[] = 
                \Filament\Forms\Components\Section::make($seccion->nombre)
                    ->schema(
                        $seccion->items->map(function ($item) use ($record) {
                            $respuesta = $record
                                ->respuestas()
                                ->where('id_item', $item->id_item)
                                ->first();

                            return \Filament\Forms\Components\ViewField::make("item_{$item->id_item}")
                                ->label($item->pregunta)
                                ->view('filament.checklist-respuesta', [
                                    'item' => $item,
                                    'respuesta' => $respuesta,
                                ]);
                        })->toArray()
                    )
                    ->collapsible();
        }

        return $schema;
    }
}
