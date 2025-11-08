<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoCombustibleResource\Pages;
use App\Models\TipoCombustible;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class TipoCombustibleResource extends Resource
{
    protected static ?string $model = TipoCombustible::class;
    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationGroup = 'Catálogos';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')->required()->maxLength(100),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_tipo_combustible')->label('ID'),
                Tables\Columns\TextColumn::make('nombre')->searchable()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipoCombustibles::route('/'),
            'create' => Pages\CreateTipoCombustible::route('/create'),
            'edit' => Pages\EditTipoCombustible::route('/{record}/edit'),
        ];
    }
}
