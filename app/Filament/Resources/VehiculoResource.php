<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehiculoResource\Pages;
use App\Models\Vehiculo;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class VehiculoResource extends Resource
{
    protected static ?string $model = Vehiculo::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Flota Vehicular';
    protected static ?string $modelLabel = 'Vehículo';
    protected static ?string $pluralModelLabel = 'Vehículos';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('placa')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->label('Placa'),
                            
                        Forms\Components\Select::make('id_tipo_vehiculo')
                            ->label('Tipo de Vehículo')
                            ->relationship('tipoVehiculo', 'nombre')
                            ->required(),
                    ]),
                    
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('marca')
                            ->maxLength(80),
                            
                        Forms\Components\TextInput::make('modelo')
                            ->maxLength(80),
                            
                        Forms\Components\TextInput::make('anio')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y') + 1),
                    ]),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('numero_serie')
                            ->maxLength(120)
                            ->label('Número de Serie'),
                            
                        Forms\Components\TextInput::make('numero_motor')
                            ->maxLength(120)
                            ->label('Número de Motor'),
                    ]),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('color')
                            ->maxLength(50),
                            
                        Forms\Components\Select::make('tipo_combustible_id')
                            ->label('Tipo de Combustible')
                            ->relationship('tipoCombustible', 'nombre')
                            ->required(),
                    ]),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DatePicker::make('vencimiento_soat')
                            ->label('Vencimiento SOAT'),
                            
                        Forms\Components\DatePicker::make('vencimiento_citv')
                            ->label('Vencimiento CITV'),
                    ]),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('km_actual')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->label('Kilometraje Actual'),
                            
                        Forms\Components\Toggle::make('propio')
                            ->default(true)
                            ->label('Vehículo Propio'),
                    ]),
                    
                Forms\Components\Select::make('estado')
                    ->options([
                        'DISPONIBLE' => 'Disponible',
                        'MANTENIMIENTO' => 'En Mantenimiento',
                        'REPARACION' => 'En Reparación',
                        'INACTIVO' => 'Inactivo',
                    ])
                    ->default('DISPONIBLE')
                    ->required(),
                    
                Forms\Components\FileUpload::make('foto_soat')
                    ->label('Foto SOAT')
                    ->directory('vehiculos/documentos')
                    ->downloadable(),
                    
                Forms\Components\FileUpload::make('foto_citv')
                    ->label('Foto CITV')
                    ->directory('vehiculos/documentos')
                    ->downloadable(),
                    
                Forms\Components\FileUpload::make('foto_tarjeta_propiedad')
                    ->label('Foto Tarjeta de Propiedad')
                    ->directory('vehiculos/documentos')
                    ->downloadable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('placa')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('marca')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('modelo')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('anio')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tipoVehiculo.nombre')
                    ->label('Tipo')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tipoCombustible.nombre')
                    ->label('Combustible'),
                    
                Tables\Columns\TextColumn::make('km_actual')
                    ->label('Kilometraje')
                    ->formatStateUsing(fn ($state) => number_format($state, 0)),
                    
                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'success' => 'DISPONIBLE',
                        'warning' => 'MANTENIMIENTO',
                        'danger' => 'INACTIVO',
                        'gray' => 'REPARACION',
                    ]),
                    
                Tables\Columns\IconColumn::make('propio')
                    ->boolean()
                    ->label('Propio'),
                    
                Tables\Columns\IconColumn::make('activo')
                    ->boolean()
                    ->label('Activo'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'DISPONIBLE' => 'Disponible',
                        'MANTENIMIENTO' => 'Mantenimiento',
                        'REPARACION' => 'Reparación',
                        'INACTIVO' => 'Inactivo',
                    ]),
                    
                Tables\Filters\Filter::make('propio')
                    ->query(fn ($query) => $query->where('propio', true))
                    ->label('Solo Vehículos Propios'),
                    
                Tables\Filters\Filter::make('activo')
                    ->query(fn ($query) => $query->where('activo', true))
                    ->label('Solo Vehículos Activos')
                    ->default(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehiculos::route('/'),
            'create' => Pages\CreateVehiculo::route('/create'),
            'edit' => Pages\EditVehiculo::route('/{record}/edit'),
        ];
    }
}