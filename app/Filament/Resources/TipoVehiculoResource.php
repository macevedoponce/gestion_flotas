<?php

namespace App\Filament\Resources;

use App\Filament\Exports\TipoVehiculoExporter;
use App\Models\TipoVehiculo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;

class TipoVehiculoResource extends Resource
{
    protected static ?string $model = TipoVehiculo::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Catálogos';
    protected static ?string $label = 'Tipo de Vehículo';
    protected static ?string $pluralLabel = 'Tipos de Vehículo';

    // ============================================================
    // FORMULARIO
    // ============================================================
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Información del Tipo de Vehículo')
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(80),

                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3),

                        Forms\Components\TextInput::make('capacidad_personas')
                            ->label('Capacidad de Personas')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),

                        Forms\Components\TextInput::make('capacidad_tanque')
                            ->label('Capacidad del Tanque (L)')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),

            ]);
    }

    // ============================================================
    // TABLA
    // ============================================================
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id_tipo')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('capacidad_personas')
                    ->label('Personas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('capacidad_tanque')
                    ->label('Tanque (L)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])

            ->filters([])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])

            ->headerActions([

                // ============================================================
                // IMPORTAR
                // ============================================================
                ImportAction::make()
                    ->label('Importar')
                    ->importer(\App\Filament\Imports\TipoVehiculoImporter::class)
                    ->color('warning'),

                // ============================================================
                // EXPORTAR
                // ============================================================
                ExportAction::make()
                    ->label('Exportar')
                    ->exporter(TipoVehiculoExporter::class)
                    ->color('success'),

                Tables\Actions\CreateAction::make(),
            ]);
    }

    // ============================================================
    // PÁGINAS
    // ============================================================
    public static function getPages(): array
    {
        return [
            'index'  => \App\Filament\Resources\TipoVehiculoResource\Pages\ListTipoVehiculos::route('/'),
            'create' => \App\Filament\Resources\TipoVehiculoResource\Pages\CreateTipoVehiculo::route('/create'),
            'edit'   => \App\Filament\Resources\TipoVehiculoResource\Pages\EditTipoVehiculo::route('/{record}/edit'),
        ];
    }
}
