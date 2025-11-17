<?php

namespace App\Filament\Resources\ChecklistPlantillaResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Preguntas';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_seccion')
                ->label('Sección')
                ->relationship('seccion', 'nombre')
                ->required(),

            Forms\Components\Select::make('id_tipo_pregunta')
                ->label('Tipo de pregunta')
                ->relationship('tipoPregunta', 'nombre')
                ->required(),

            Forms\Components\Textarea::make('pregunta')
                ->label('Pregunta')
                ->rows(2)
                ->required(),

            Forms\Components\Toggle::make('obligatorio')
                ->label('Obligatoria')
                ->default(false),

            Forms\Components\KeyValue::make('configuracion')
                ->label('Configuración (opciones, rangos, etc.)')
                ->keyLabel('Clave')
                ->valueLabel('Valor')
                ->addButtonLabel('Agregar configuración')
                ->nullable(),

            Forms\Components\TextInput::make('orden')
                ->label('Orden')
                ->numeric()
                ->default(0),
        ])->columns(2);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('seccion.nombre')
                    ->label('Sección')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipoPregunta.nombre')
                    ->label('Tipo'),

                Tables\Columns\TextColumn::make('pregunta')
                    ->label('Pregunta')
                    ->limit(40),

                Tables\Columns\IconColumn::make('obligatorio')
                    ->label('Obligatoria')
                    ->boolean(),

                Tables\Columns\TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
