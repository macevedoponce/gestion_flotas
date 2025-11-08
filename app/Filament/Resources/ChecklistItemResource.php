<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChecklistItemResource\Pages;
use App\Models\ChecklistItem;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class ChecklistItemResource extends Resource
{
    protected static ?string $model = ChecklistItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Checklist';
    protected static ?string $label = 'Item de Checklist';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_seccion')
                ->label('Sección')
                ->relationship('seccion', 'nombre')
                ->required(),

            Forms\Components\Select::make('id_tipo_pregunta')
                ->label('Tipo de Pregunta')
                ->relationship('tipoPregunta', 'nombre')
                ->required(),

            Forms\Components\TextInput::make('pregunta')->required(),
            Forms\Components\Textarea::make('descripcion')->rows(2),
            Forms\Components\Toggle::make('obligatorio')->label('Obligatorio')->default(false),
            Forms\Components\TextInput::make('orden')->numeric(),
            Forms\Components\KeyValue::make('configuracion')->label('Configuración Adicional'),
            Forms\Components\Toggle::make('activo')->default(true),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('seccion.nombre')->label('Sección'),
            Tables\Columns\TextColumn::make('tipoPregunta.nombre')->label('Tipo de Pregunta'),
            Tables\Columns\TextColumn::make('pregunta')->wrap(),
            Tables\Columns\IconColumn::make('obligatorio')->boolean(),
            Tables\Columns\IconColumn::make('activo')->boolean(),
        ])
        ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklistItems::route('/'),
            'create' => Pages\CreateChecklistItem::route('/create'),
            'edit' => Pages\EditChecklistItem::route('/{record}/edit'),
        ];
    }
}
