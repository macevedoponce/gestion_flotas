<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChecklistTipoPreguntaResource\Pages;
use App\Models\ChecklistTipoPregunta;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class ChecklistTipoPreguntaResource extends Resource
{
    protected static ?string $model = ChecklistTipoPregunta::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $modelLabel = 'Tipo de Pregunta';
    protected static ?string $pluralModelLabel = 'Tipos de Pregunta';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('codigo')
                ->label('Código interno')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(50)
                ->helperText('Ej: BOOLEANO, TEXTO, IMAGEN'),

            Forms\Components\TextInput::make('nombre')
                ->label('Nombre visible')
                ->required()
                ->maxLength(100),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('codigo')
                ->label('Código')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('nombre')
                ->label('Nombre')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Creado')
                ->dateTime(),
        ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklistTipoPreguntas::route('/'),
            'create' => Pages\CreateChecklistTipoPregunta::route('/create'),
            'edit' => Pages\EditChecklistTipoPregunta::route('/{record}/edit'),
        ];
    }
}
