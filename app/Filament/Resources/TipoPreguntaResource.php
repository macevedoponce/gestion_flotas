<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoPreguntaResource\Pages;
use App\Models\TipoPregunta;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class TipoPreguntaResource extends Resource
{
    protected static ?string $model = TipoPregunta::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Checklist';
    protected static ?string $label = 'Tipo de Pregunta';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')->required(),
            Forms\Components\Select::make('estructura_respuesta')
                ->options([
                    'booleano' => 'Sí / No',
                    'texto' => 'Texto',
                    'numero' => 'Número',
                    'fecha' => 'Fecha',
                    'imagen' => 'Imagen',
                    'json' => 'JSON',
                ])->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nombre'),
            Tables\Columns\TextColumn::make('estructura_respuesta'),
        ])
        ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipoPreguntas::route('/'),
            'create' => Pages\CreateTipoPregunta::route('/create'),
            'edit' => Pages\EditTipoPregunta::route('/{record}/edit'),
        ];
    }
}
