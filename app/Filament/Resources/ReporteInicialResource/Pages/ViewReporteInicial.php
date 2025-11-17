<?php

namespace App\Filament\Resources\ReporteInicialResource\Pages;

use App\Filament\Resources\ReporteInicialResource;
use App\Filament\Resources\ChecklistEjecucionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;

class ViewReporteInicial extends ViewRecord
{
    protected static string $resource = ReporteInicialResource::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Tabs::make('ReporteInicialTabs')
                ->tabs([

                    // TAB: Información general
                    Forms\Components\Tabs\Tab::make('Información')
                        ->schema([
                            Forms\Components\TextInput::make('id_jornada')->disabled(),
                            Forms\Components\TextInput::make('km_inicial')->disabled(),
                            Forms\Components\Textarea::make('motivo_traslado')->disabled(),
                            Forms\Components\TextInput::make('destino')->disabled(),
                            Forms\Components\TextInput::make('acompanantes')->disabled(),
                        ]),

                    // TAB: Checklist del conductor
                    Forms\Components\Tabs\Tab::make('Checklist')
                        ->schema([
                            Forms\Components\ViewField::make('checklist_view')
                                ->hiddenLabel()
                                ->view('filament.view-checklist-from-reporte', [
                                    'reporte' => $this->record,
                                ]),
                        ]),

                ])
                ->columnSpanFull()
        ];
    }
}
