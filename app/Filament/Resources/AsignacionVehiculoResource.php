<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsignacionVehiculoResource\Pages;
use App\Models\AsignacionVehiculo;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class AsignacionVehiculoResource extends Resource
{
    protected static ?string $model = AsignacionVehiculo::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-circle';
    protected static ?string $navigationGroup = 'Proyectos y Solicitudes';
    protected static ?string $pluralLabel = 'Asignaciones de Vehículos';
    protected static ?string $label = 'Asignación de Vehículo';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_solicitud')
                ->label('Solicitud')
                ->relationship('solicitud', 'id_solicitud')
                ->required()
                ->searchable(),

            Forms\Components\Select::make('id_proyecto')
                ->label('Proyecto')
                ->relationship('proyecto', 'descripcion')
                ->required()
                ->searchable(),

            Forms\Components\Select::make('id_vehiculo')
                ->label('Vehículo')
                ->relationship('vehiculo', 'placa')
                ->required()
                ->searchable(),

            Forms\Components\Select::make('id_conductor')
                ->label('Conductor')
                ->relationship('conductor', 'nombre_completo')
                ->required()
                ->searchable(),

            Forms\Components\Select::make('id_jefe_control')
                ->label('Jefe de Control')
                ->relationship('jefeControl', 'name')
                ->required()
                ->searchable(),

            Forms\Components\DatePicker::make('fecha_asignacion')
                ->label('Fecha de Asignación')
                ->required(),

            Forms\Components\DatePicker::make('fecha_finalizacion')
                ->label('Fecha de Finalización'),

            Forms\Components\Select::make('estado')
                ->label('Estado')
                ->options([
                    'ASIGNADO' => 'Asignado',
                    'EN_CURSO' => 'En Curso',
                    'FINALIZADO' => 'Finalizado',
                ])
                ->default('ASIGNADO'),

            Forms\Components\Textarea::make('observaciones')
                ->label('Observaciones')
                ->rows(3),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id_asignacion')->label('ID')->sortable(),
            Tables\Columns\TextColumn::make('solicitud.id_solicitud')->label('Solicitud'),
            Tables\Columns\TextColumn::make('proyecto.descripcion')->label('Proyecto')->limit(30),
            Tables\Columns\TextColumn::make('vehiculo.placa')->label('Vehículo'),
            Tables\Columns\TextColumn::make('conductor.nombre_completo')->label('Conductor'),
            Tables\Columns\TextColumn::make('jefeControl.name')->label('Jefe de Control'),
            Tables\Columns\TextColumn::make('estado')->label('Estado')->badge(),
            Tables\Columns\TextColumn::make('fecha_asignacion')->date('d/m/Y'),
            Tables\Columns\TextColumn::make('fecha_finalizacion')->date('d/m/Y'),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsignacionVehiculos::route('/'),
            'create' => Pages\CreateAsignacionVehiculo::route('/create'),
            'edit' => Pages\EditAsignacionVehiculo::route('/{record}/edit'),
        ];
    }
}
