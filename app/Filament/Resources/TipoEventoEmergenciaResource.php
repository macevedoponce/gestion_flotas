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
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Catálogos';
    protected static ?string $modelLabel = 'Tipo de Evento de Emergencia';
    protected static ?string $pluralModelLabel = 'Tipos de Evento de Emergencia';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(80),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(4),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_tipo_evento')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
