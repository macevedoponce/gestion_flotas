<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MantenimientoResource\Pages;
use App\Models\Mantenimiento;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class MantenimientoResource extends Resource
{
    protected static ?string $model = Mantenimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Mantenimiento';
    protected static ?string $pluralModelLabel = 'Mantenimientos';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([

            Forms\Components\Section::make('Datos del Mantenimiento')
                ->schema([
                    Forms\Components\Select::make('id_vehiculo')
                        ->relationship('vehiculo', 'placa')
                        ->label('Vehículo')
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('tipo')
                        ->options([
                            'PREVENTIVO' => 'Preventivo',
                            'CORRECTIVO' => 'Correctivo',
                        ])
                        ->required(),

                    Forms\Components\DatePicker::make('fecha_ingreso')
                        ->required(),
                    Forms\Components\DatePicker::make('fecha_estimada_entrega'),
                    Forms\Components\DatePicker::make('fecha_entrega_real')
                        ->label('Fecha de entrega real'),
                ])->columns(3),

            Forms\Components\Section::make('Taller')
                ->schema([
                    Forms\Components\TextInput::make('taller_nombre'),
                    Forms\Components\TextInput::make('taller_contacto'),
                ])->columns(2),

            Forms\Components\Section::make('Detalles')
                ->schema([
                    Forms\Components\Textarea::make('motivo'),
                    Forms\Components\KeyValue::make('trabajos')
                        ->label('Trabajos a realizar')
                        ->keyLabel('Tarea')
                        ->valueLabel('Descripción'),

                    Forms\Components\TextInput::make('km_registrado')->numeric(),
                    Forms\Components\TextInput::make('costo_estimado')->numeric(),
                    Forms\Components\TextInput::make('costo_real')->numeric(),
                ])->columns(2),

            Forms\Components\Section::make('Prórroga')
                ->schema([
                    Forms\Components\DatePicker::make('fecha_solicitud_prorroga'),
                    Forms\Components\Textarea::make('motivo_prorroga'),
                    Forms\Components\DatePicker::make('nueva_fecha_entrega'),
                    Forms\Components\Select::make('estado_prorroga')
                        ->options([
                            'PENDIENTE' => 'Pendiente',
                            'APROBADA' => 'Aprobada',
                            'RECHAZADA' => 'Rechazada',
                        ]),
                ])->columns(2),

            Forms\Components\Section::make('Evidencias')
                ->schema([
                    Forms\Components\FileUpload::make('fotos')
                        ->directory('mantenimientos/fotos')
                        ->multiple()
                        ->image(),

                    Forms\Components\FileUpload::make('documentos')
                        ->directory('mantenimientos/docs')
                        ->multiple(),
                ]),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([

            Tables\Columns\TextColumn::make('vehiculo.placa')->label('Placa')->sortable()->searchable(),

            Tables\Columns\BadgeColumn::make('estado')
                ->colors([
                    'warning' => 'EN_PROCESO',
                    'success' => 'FINALIZADO',
                    'danger' => 'PRORROGA_SOLICITADA',
                    'gray' => 'PROGRAMADO',
                ]),

            Tables\Columns\TextColumn::make('fecha_ingreso')->date(),
            Tables\Columns\TextColumn::make('fecha_estimada_entrega')->date()->label('Entrega Estimada'),
            Tables\Columns\TextColumn::make('nueva_fecha_entrega')->date()->label('Nueva Estimada'),

        ])->filters([
            Tables\Filters\SelectFilter::make('estado')
                ->options([
                    'PROGRAMADO' => 'Programado',
                    'EN_PROCESO' => 'En Proceso',
                    'PRORROGA_SOLICITADA' => 'Prórroga Solicitada',
                    'FINALIZADO' => 'Finalizado',
                ]),
        ])
          ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMantenimientos::route('/'),
            'create' => Pages\CreateMantenimiento::route('/create'),
            'edit' => Pages\EditMantenimiento::route('/{record}/edit'),
        ];
    }
}
