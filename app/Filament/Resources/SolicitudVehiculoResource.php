<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitudVehiculoResource\Pages;
use App\Models\SolicitudVehiculo;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Resources\Resource;
use Illuminate\Support\Carbon;

class SolicitudVehiculoResource extends Resource
{
    protected static ?string $model = SolicitudVehiculo::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Proyectos y Solicitudes';
    protected static ?string $pluralLabel = 'Solicitudes de Vehículo';
    protected static ?string $label = 'Solicitud de Vehículo';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Section::make('Información General')
                ->schema([
                    Forms\Components\Select::make('id_usuario_solicitante')
                        ->label('Solicitante')
                        ->relationship('solicitante', 'name')
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('id_tipo_vehiculo')
                        ->label('Tipo de Vehículo')
                        ->relationship('tipoVehiculo', 'nombre')
                        ->required(),
                    Forms\Components\TextInput::make('codigo_anexo')
                        ->label('Código Anexo')
                        ->maxLength(14),
                    Forms\Components\Textarea::make('descripcion_proyecto')
                        ->label('Descripción del Proyecto')
                        ->rows(2),
                    Forms\Components\TextInput::make('motivo_trabajo')
                        ->label('Motivo del Trabajo'),
                    Forms\Components\TextInput::make('lugar_trabajo')
                        ->label('Lugar de Trabajo'),
                ])->columns(2),

            Forms\Components\Section::make('Periodo de Uso')
                ->schema([
                    Forms\Components\Toggle::make('indeterminado')
                        ->label('Periodo Indeterminado')
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $set('fecha_fin', null);
                                $set('cantidad_dias', null);
                            }
                        }),
                    Forms\Components\DatePicker::make('fecha_inicio')
                        ->label('Fecha Inicio')
                        ->required()
                        ->minDate(now())
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, Get $get) {
                            if ($state && !$get('indeterminado')) {
                                self::calcularFechaFin($state, $get('cantidad_dias'), $set);
                            }
                        }),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\TextInput::make('cantidad_dias')
                                ->numeric()
                                ->label('Cantidad de Días')
                                ->minValue(1)
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                    if (!$get('indeterminado') && $get('fecha_inicio')) {
                                        self::calcularFechaFin($get('fecha_inicio'), $state, $set);
                                    }
                                }),
                            Forms\Components\DatePicker::make('fecha_fin')
                                ->label('Fecha Fin')
                                ->minDate(fn (Get $get) => $get('fecha_inicio') ?: now())
                                ->disabled(fn (Get $get) => $get('indeterminado'))
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                    if (!$get('indeterminado') && $get('fecha_inicio') && $state) {
                                        self::calcularCantidadDias($get('fecha_inicio'), $state, $set);
                                    }
                                }),
                        ])
                        ->hidden(fn (Get $get) => $get('indeterminado'))
                        ->columns(2),
                ])->columns(2),

            Forms\Components\Section::make('Información del Conductor')
                ->schema([
                    Forms\Components\Toggle::make('requiere_conductor')
                        ->label('La empresa proveerá conductor')
                        ->default(true)
                        ->live()
                        ->helperText('Si está activado, la empresa proporcionará el conductor. Si está desactivado, debe ingresar los datos del conductor externo.')
                        ->afterStateUpdated(function ($state, callable $set) {
                            // Limpiar campos de conductor externo cuando se activa (empresa provee conductor)
                            if ($state) {
                                $set('conductor_externo_nombres', null);
                                $set('conductor_externo_dni', null);
                                $set('conductor_externo_celular', null);
                                $set('conductor_externo_licencia', null);
                            }
                        }),
                    Forms\Components\Fieldset::make('Conductor Externo')
                        ->label('Datos del Conductor Externo (solo si la empresa NO provee conductor)')
                        ->schema([
                            Forms\Components\TextInput::make('conductor_externo_nombres')
                                ->label('Nombres Completos')
                                ->maxLength(150)
                                ->required(fn (Get $get) => !$get('requiere_conductor'))
                                ->placeholder('Ej: Juan Carlos Pérez García'),
                            Forms\Components\TextInput::make('conductor_externo_dni')
                                ->label('DNI')
                                ->maxLength(8)
                                ->required(fn (Get $get) => !$get('requiere_conductor'))
                                ->placeholder('Ej: 87654321'),
                            Forms\Components\TextInput::make('conductor_externo_celular')
                                ->label('Celular')
                                ->maxLength(9)
                                ->required(fn (Get $get) => !$get('requiere_conductor'))
                                ->placeholder('Ej: 987654321'),
                            Forms\Components\TextInput::make('conductor_externo_licencia')
                                ->label('Licencia de Conducir')
                                ->maxLength(80)
                                ->required(fn (Get $get) => !$get('requiere_conductor'))
                                ->placeholder('Ej: AIIIB-123456'),
                        ])->columns(2)
                        ->visible(fn (Get $get) => !$get('requiere_conductor')), // MOSTRAR solo cuando NO requiere conductor de la empresa
                ]),

            Forms\Components\Section::make('Estado')
                ->schema([
                    Forms\Components\Select::make('estado')
                        ->label('Estado')
                        ->options([
                            'PENDIENTE' => 'Pendiente',
                            'APROBADA' => 'Aprobada',
                            'RECHAZADA' => 'Rechazada',
                        ])
                        ->default('PENDIENTE'),
                ]),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_solicitud')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('solicitante.name')
                    ->label('Solicitante')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipoVehiculo.nombre')
                    ->label('Tipo Vehículo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Fecha Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fecha Fin')
                    ->date('d/m/Y')
                    ->placeholder('Indeterminado')
                    ->sortable(),
                Tables\Columns\IconColumn::make('requiere_conductor')
                    ->label('Conductor')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->getStateUsing(fn ($record) => $record?->requiere_conductor ?? true)
                    ->tooltip(fn ($record): string => $record?->requiere_conductor ? 'Conductor de la empresa' : 'Conductor externo'),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDIENTE' => 'warning',
                        'APROBADA' => 'success',
                        'RECHAZADA' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha Solicitud')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'PENDIENTE' => 'Pendiente',
                        'APROBADA' => 'Aprobada',
                        'RECHAZADA' => 'Rechazada',
                    ]),
                Tables\Filters\Filter::make('requiere_conductor')
                    ->label('Conductor de Empresa')
                    ->query(fn ($query) => $query->where('requiere_conductor', true)),
                Tables\Filters\Filter::make('conductor_externo')
                    ->label('Conductor Externo')
                    ->query(fn ($query) => $query->where('requiere_conductor', false)),
                Tables\Filters\Filter::make('fecha_inicio')
                    ->form([
                        Forms\Components\DatePicker::make('fecha_desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('fecha_hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['fecha_desde'], fn ($q, $date) => $q->whereDate('fecha_inicio', '>=', $date))
                            ->when($data['fecha_hasta'], fn ($q, $date) => $q->whereDate('fecha_inicio', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSolicitudVehiculos::route('/'),
            'create' => Pages\CreateSolicitudVehiculo::route('/create'),
            'edit' => Pages\EditSolicitudVehiculo::route('/{record}/edit'),
        ];
    }

    /**
     * Calcula la fecha fin basada en la fecha inicio y cantidad de días
     */
    private static function calcularFechaFin(?string $fechaInicio, ?int $cantidadDias, callable $set): void
    {
        if ($fechaInicio && $cantidadDias) {
            $fechaFin = Carbon::parse($fechaInicio)->addDays($cantidadDias);
            $set('fecha_fin', $fechaFin->format('Y-m-d'));
        }
    }

    /**
     * Calcula la cantidad de días basada en fecha inicio y fecha fin
     */
    private static function calcularCantidadDias(?string $fechaInicio, ?string $fechaFin, callable $set): void
    {
        if ($fechaInicio && $fechaFin) {
            $dias = Carbon::parse($fechaInicio)->diffInDays(Carbon::parse($fechaFin));
            $set('cantidad_dias', $dias);
        }
    }
}