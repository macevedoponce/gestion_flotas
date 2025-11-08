<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChecklistPlantillaResource\Pages;
use App\Models\ChecklistPlantilla;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class ChecklistPlantillaResource extends Resource
{
    protected static ?string $model = ChecklistPlantilla::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Checklist';
    protected static ?string $label = 'Plantilla de Checklist';
    protected static ?string $pluralLabel = 'Plantillas de Checklist';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')->required(),
            Forms\Components\Textarea::make('descripcion')->rows(2),
            Forms\Components\Select::make('id_tipo_vehiculo')
                ->label('Tipo de Vehículo')
                ->relationship('tipoVehiculo', 'nombre')
                ->required(),
            Forms\Components\Toggle::make('activo')->default(true)->label('Activo'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nombre')->searchable(),
            Tables\Columns\TextColumn::make('tipoVehiculo.nombre')->label('Tipo de Vehículo'),
            Tables\Columns\IconColumn::make('activo')->boolean(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklistPlantillas::route('/'),
            'create' => Pages\CreateChecklistPlantilla::route('/create'),
            'edit' => Pages\EditChecklistPlantilla::route('/{record}/edit'),
        ];
    }
}
