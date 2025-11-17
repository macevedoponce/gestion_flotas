<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehiculoResource\Pages;
use App\Models\Vehiculo;
use App\Models\TipoVehiculo;
use App\Models\TipoCombustible;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Imports\VehiculoImporter;
use App\Filament\Exports\VehiculoExporter;

class VehiculoResource extends Resource
{
    protected static ?string $model = Vehiculo::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Core del Sistema';
    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Vehículo';
    protected static ?string $pluralLabel = 'Vehículos';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Identificación')
                ->schema([
                    Forms\Components\TextInput::make('placa')
                        ->label('Placa')
                        ->required()
                        ->maxLength(20)
                        ->unique(ignoreRecord: true),

                    Forms\Components\Select::make('id_tipo_vehiculo')
                        ->label('Tipo de Vehículo')
                        ->relationship('tipoVehiculo', 'nombre')
                        ->searchable()
                        ->nullable(),

                    Forms\Components\Select::make('tipo_combustible_id')
                        ->label('Tipo de Combustible')
                        ->relationship('tipoCombustible', 'nombre')
                        ->searchable()
                        ->nullable(),
                ])->columns(3),

            Forms\Components\Section::make('Especificaciones')
                ->schema([
                    Forms\Components\TextInput::make('marca')->label('Marca')->maxLength(80),
                    Forms\Components\TextInput::make('modelo')->label('Modelo')->maxLength(80),
                    Forms\Components\TextInput::make('numero_serie')->label('N° Serie')->maxLength(120),
                    Forms\Components\TextInput::make('numero_motor')->label('N° Motor')->maxLength(120),
                    Forms\Components\TextInput::make('color')->label('Color')->maxLength(50),
                    Forms\Components\TextInput::make('anio')->label('Año')->numeric()->nullable(),
                    Forms\Components\TextInput::make('km_actual')->label('KM Actual')->numeric()->default(0),
                ])
                ->columns(3),

            Forms\Components\Section::make('Documentos')
                ->schema([
                    Forms\Components\DatePicker::make('vencimiento_soat')
                        ->label('Vencimiento SOAT')
                        ->nullable(),

                    Forms\Components\DatePicker::make('vencimiento_citv')
                        ->label('Vencimiento CITV')
                        ->nullable(),

                    Forms\Components\FileUpload::make('foto_soat')
                        ->label('Foto SOAT')
                        ->image()->directory('vehiculos')
                        ->nullable(),

                    Forms\Components\FileUpload::make('foto_citv')
                        ->label('Foto CITV')
                        ->image()->directory('vehiculos')
                        ->nullable(),

                    Forms\Components\FileUpload::make('foto_tarjeta_propiedad')
                        ->label('Tarjeta de Propiedad')
                        ->image()->directory('vehiculos')
                        ->nullable(),
                ])
                ->columns(3),

            Forms\Components\Section::make('Estado')
                ->schema([
                    Forms\Components\Select::make('estado')
                        ->label('Estado Operativo')
                        ->options([
                            'DISPONIBLE' => 'DISPONIBLE',
                            'ASIGNADO'   => 'ASIGNADO',
                            'TALLER'     => 'TALLER',
                            'INACTIVO'   => 'INACTIVO',
                        ])
                        ->default('DISPONIBLE'),

                    Forms\Components\Toggle::make('propio')
                        ->label('Propio')
                        ->default(true),

                    Forms\Components\Toggle::make('activo')
                        ->label('Activo')
                        ->default(true),
                ])
                ->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('placa')
                    ->label('Placa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipoVehiculo.nombre')
                    ->label('Tipo'),

                Tables\Columns\TextColumn::make('tipoCombustible.nombre')
                    ->label('Combustible'),

                Tables\Columns\TextColumn::make('marca')->label('Marca'),
                Tables\Columns\TextColumn::make('modelo')->label('Modelo'),

                Tables\Columns\TextColumn::make('km_actual')
                    ->label('KM')
                    ->numeric(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'success' => 'DISPONIBLE',
                        'warning' => 'ASIGNADO',
                        'danger'  => 'INACTIVO',
                        'info'    => 'TALLER',
                    ]),

                Tables\Columns\IconColumn::make('propio')
                    ->boolean()
                    ->label('Propio'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])

            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\ImportAction::make()
                    ->importer(VehiculoImporter::class)
                    ->label('Importar vehiculos'),
                Tables\Actions\ExportAction::make()
                    ->exporter(VehiculoExporter::class)
                    ->label('Exportar'),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVehiculos::route('/'),
            'create' => Pages\CreateVehiculo::route('/create'),
            'edit'   => Pages\EditVehiculo::route('/{record}/edit'),
        ];
    }
}
