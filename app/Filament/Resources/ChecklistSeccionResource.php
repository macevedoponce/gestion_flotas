<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChecklistSeccionResource\Pages;
use App\Models\ChecklistSeccion;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class ChecklistSeccionResource extends Resource
{
    protected static ?string $model = ChecklistSeccion::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Checklist';
    protected static ?string $label = 'Sección de Checklist';
    protected static ?string $pluralLabel = 'Secciones de Checklist';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_plantilla')
                ->label('Plantilla')
                ->relationship('plantilla', 'nombre')
                ->required(),
            Forms\Components\TextInput::make('nombre')->required(),
            Forms\Components\Textarea::make('descripcion')->rows(2),
            Forms\Components\TextInput::make('orden')->numeric(),
            Forms\Components\Toggle::make('activo')->default(true)->label('Activo'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('plantilla.nombre')->label('Plantilla'),
            Tables\Columns\TextColumn::make('nombre'),
            Tables\Columns\TextColumn::make('orden'),
            Tables\Columns\IconColumn::make('activo')->boolean(),
        ])
        ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklistSeccions::route('/'),
            'create' => Pages\CreateChecklistSeccion::route('/create'),
            'edit' => Pages\EditChecklistSeccion::route('/{record}/edit'),
        ];
    }
}
