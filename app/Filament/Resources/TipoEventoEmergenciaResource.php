<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoEventoEmergenciaResource\Pages;
use App\Models\TipoEventoEmergencia;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class TipoEventoEmergenciaResource extends Resource
{
    protected static ?string $model = TipoEventoEmergencia::class;
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationGroup = 'Mantenimientos y Emergencias';
    protected static ?string $pluralLabel = 'Tipos de Emergencias';
    protected static ?string $label = 'Tipo de Emergencia';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(80),
            Forms\Components\Textarea::make('descripcion')
                ->label('Descripción')
                ->rows(3)
                ->nullable(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_tipo_evento')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('nombre')->label('Nombre')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('descripcion')->label('Descripción')->limit(60),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipoEventoEmergencias::route('/'),
            'create' => Pages\CreateTipoEventoEmergencia::route('/create'),
            'edit' => Pages\EditTipoEventoEmergencia::route('/{record}/edit'),
        ];
    }
}
