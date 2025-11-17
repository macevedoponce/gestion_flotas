<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChecklistPlantillaResource\Pages;
use App\Filament\Resources\ChecklistPlantillaResource\RelationManagers\SeccionesRelationManager;
use App\Filament\Resources\ChecklistPlantillaResource\RelationManagers\ItemsRelationManager;
use App\Models\ChecklistPlantilla;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class ChecklistPlantillaResource extends Resource
{
    protected static ?string $model = ChecklistPlantilla::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $modelLabel = 'Checklist';
    protected static ?string $pluralModelLabel = 'Checklists';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')
                ->label('Nombre del checklist')
                ->required()
                ->maxLength(150),

            Forms\Components\Textarea::make('descripcion')
                ->label('Descripción')
                ->rows(3),

            Forms\Components\Select::make('id_tipo_vehiculo')
                ->label('Tipo de vehículo')
                ->relationship('tipoVehiculo', 'nombre')
                ->searchable()
                ->required(),

            Forms\Components\Toggle::make('activo')
                ->label('Activo')
                ->default(true),
        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_plantilla')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipoVehiculo.nombre')
                    ->label('Tipo de vehículo'),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            SeccionesRelationManager::class,
            ItemsRelationManager::class,
        ];
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
