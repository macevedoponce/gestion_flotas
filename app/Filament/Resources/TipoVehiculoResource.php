<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoVehiculoResource\Pages;
use App\Models\TipoVehiculo;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class TipoVehiculoResource extends Resource
{
    protected static ?string $model = TipoVehiculo::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Catálogos';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(80),
                    
                Forms\Components\Textarea::make('descripcion')
                    ->maxLength(255)
                    ->columnSpanFull(),
                    
                Forms\Components\TextInput::make('capacidad_personas')
                    ->numeric()
                    ->integer(),
                    
                Forms\Components\TextInput::make('capacidad_tanque')
                    ->numeric()
                    ->step(0.01),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_tipo')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('capacidad_personas')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('capacidad_tanque')
                    ->sortable(),
            ])
            ->filters([
                // Filtro básico por nombre
                Tables\Filters\Filter::make('nombre')
                    ->form([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Buscar por nombre'),
                    ])
                    ->query(function ($query, $data) {
                        return $query->when(
                            $data['nombre'],
                            fn ($query, $nombre) => $query->where('nombre', 'ilike', "%{$nombre}%")
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipoVehiculos::route('/'),
            'create' => Pages\CreateTipoVehiculo::route('/create'),
            'edit' => Pages\EditTipoVehiculo::route('/{record}/edit'),
        ];
    }
}