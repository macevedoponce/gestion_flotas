<?php

namespace App\Filament\Resources\ChecklistPlantillaResource\RelationManagers;

use App\Models\ChecklistSeccion;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class SeccionesRelationManager extends RelationManager
{
    protected static string $relationship = 'secciones';
    protected static ?string $title = 'Secciones';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')
                ->label('Nombre de la sección')
                ->required()
                ->maxLength(150),

            Forms\Components\TextInput::make('orden')
                ->label('Orden')
                ->numeric()
                ->default(0),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Sección')
                    ->sortable(),

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
