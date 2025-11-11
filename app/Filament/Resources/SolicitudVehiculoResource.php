<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitudVehiculoResource\Pages;
use App\Models\SolicitudVehiculo;
use App\Models\Proyecto;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use App\Models\Conductor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Tabs;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SolicitudVehiculoResource extends Resource
{
    protected static ?string $model = SolicitudVehiculo::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Proyectos y Solicitudes';
    protected static ?string $pluralLabel = 'Solicitudes de Vehículo';
    protected static ?string $label = 'Solicitud de Vehículo';
    protected static ?string $slug = 'solicitudes-vehiculos';
    protected static ?string $recordTitleAttribute = 'id_solicitud';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('flujo')
                ->tabs([
                    // 🟦 Solicitud (editable solo si estado = PENDIENTE)
                    Tabs\Tab::make('Solicitud')
                        ->icon('heroicon-m-clipboard-document')
                        ->schema([
                            Forms\Components\Section::make('Información General')
                                ->schema([
                                    Forms\Components\Select::make('id_proyecto')
                                        ->label('Proyecto')
                                        ->options(fn () =>
                                            Proyecto::query()
                                                ->when(Auth::user()?->hasRole('Jefe de Proyecto'), fn($q) => $q->where('encargado_id', Auth::id()))
                                                ->orderBy('proyecto')
                                                ->pluck('proyecto', 'id_proyecto')
                                        )
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->disabled(fn ($record) => $record && $record->estado !== 'PENDIENTE'),

                                    // Chips / botones para tipos de vehículo (selección única)
                                    Forms\Components\ToggleButtons::make('id_tipo_vehiculo')
                                        ->label('Tipo de Vehículo')
                                        ->options(fn () => TipoVehiculo::query()->orderBy('nombre')->pluck('nombre', 'id_tipo')->toArray())
                                        ->inline()
                                        ->required()
                                        ->disabled(fn ($record) => $record && $record->estado !== 'PENDIENTE'),

                                    Forms\Components\Textarea::make('motivo_trabajo')
                                        ->label('Motivo del Trabajo')
                                        ->rows(2)
                                        ->columnSpanFull()
                                        ->disabled(fn ($record) => $record && $record->estado !== 'PENDIENTE'),

                                    Forms\Components\TextInput::make('lugar_trabajo')
                                        ->label('Lugar de Trabajo')
                                        ->required()
                                        ->disabled(fn ($record) => $record && $record->estado !== 'PENDIENTE'),
                                ])
                                ->columns(2),

                            Forms\Components\Section::make('Periodo de Uso')
                                ->schema([
                                    Forms\Components\Toggle::make('indeterminado')
                                        ->label('Periodo indeterminado')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                                $set('fecha_fin', null);
                                                $set('cantidad_dias', null);
                                            }
                                        })
                                        ->disabled(fn ($record) => $record && $record->estado !== 'PENDIENTE'),

                                    Forms\Components\DatePicker::make('fecha_inicio')
                                        ->label('Fecha Inicio')
                                        ->minDate(now())
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, Forms\Get $get) {
                                            if ($state && !$get('indeterminado')) {
                                                self::calcularFechaFin($state, $get('cantidad_dias'), $set);
                                            }
                                        })
                                        ->disabled(fn ($record) => $record && $record->estado !== 'PENDIENTE'),

                                    Forms\Components\Group::make()
                                        ->schema([
                                            Forms\Components\TextInput::make('cantidad_dias')
                                                ->numeric()
                                                ->label('Cantidad de Días')
                                                ->minValue(1)
                                                ->reactive()
                                                ->afterStateUpdated(function ($state, callable $set, Forms\Get $get) {
                                                    if (!$get('indeterminado') && $get('fecha_inicio')) {
                                                        self::calcularFechaFin($get('fecha_inicio'), $state, $set);
                                                    }
                                                }),

                                            Forms\Components\DatePicker::make('fecha_fin')
                                                ->label('Fecha Fin')
                                                ->minDate(fn (Forms\Get $get) => $get('fecha_inicio') ?: now())
                                                ->disabled(fn (Forms\Get $get) => $get('indeterminado'))
                                                ->reactive()
                                                ->afterStateUpdated(function ($state, callable $set, Forms\Get $get) {
                                                    if (!$get('indeterminado') && $get('fecha_inicio') && $state) {
                                                        self::calcularCantidadDias($get('fecha_inicio'), $state, $set);
                                                    }
                                                }),
                                        ])
                                        ->hidden(fn (Forms\Get $get) => $get('indeterminado'))
                                        ->columns(2),
                                ])
                                ->columns(2),

                            Forms\Components\Section::make('Información del Conductor')
                                ->schema([
                                    Forms\Components\Toggle::make('requiere_conductor')
                                        ->label('¿La empresa proveerá conductor?')
                                        ->default(true)
                                        ->reactive()
                                        ->helperText('Si se desactiva, ingresa los datos del conductor externo.')
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                                $set('conductor_externo_nombres', null);
                                                $set('conductor_externo_dni', null);
                                                $set('conductor_externo_celular', null);
                                                $set('conductor_externo_licencia', null);
                                            }
                                        })
                                        ->disabled(fn ($record) => $record && $record->estado !== 'PENDIENTE'),

                                    Forms\Components\Fieldset::make('Conductor Externo')
                                        ->schema([
                                            Forms\Components\TextInput::make('conductor_externo_nombres')
                                                ->label('Nombres')
                                                ->required(fn (Forms\Get $get) => !$get('requiere_conductor')),
                                            Forms\Components\TextInput::make('conductor_externo_dni')
                                                ->label('DNI')
                                                ->maxLength(12)
                                                ->required(fn (Forms\Get $get) => !$get('requiere_conductor')),
                                            Forms\Components\TextInput::make('conductor_externo_celular')
                                                ->label('Celular'),
                                            Forms\Components\TextInput::make('conductor_externo_licencia')
                                                ->label('Licencia'),
                                        ])
                                        ->columns(2)
                                        ->visible(fn (Forms\Get $get) => !$get('requiere_conductor')),
                                ]),
                        ]),

                    // 🟧 Asignación (solo Control/Monitoreo & Super Admin; estado PENDIENTE)
                    Tabs\Tab::make('Asignación')
                        ->icon('heroicon-m-clipboard-check')
                        ->visible(fn ($record) =>
                            $record
                            && $record->estado === 'PENDIENTE'
                            && Auth::user()->hasAnyRole(['Jefe de Control y Monitoreo','Super Admin'])
                        )
                        ->schema([
                            Forms\Components\Group::make()
                                ->statePath('asignacion')
                                ->schema([
                                    Forms\Components\Select::make('id_vehiculo')
                                        ->label('Vehículo disponible')
                                        ->options(fn () =>
                                            Vehiculo::query()
                                                ->where('estado', 'DISPONIBLE')
                                                ->orderBy('placa')
                                                ->pluck('placa', 'id_vehiculo')
                                        )
                                        ->searchable()
                                        ->required(),

                                    Forms\Components\Select::make('id_conductor')
                                        ->label('Conductor interno disponible')
                                        ->options(fn ($get, $record) =>
                                            ($record && $record->requiere_conductor)
                                                ? Conductor::query()
                                                    ->where('estado_disponibilidad','DISPONIBLE')
                                                    ->orderBy('nombre_completo')
                                                    ->pluck('nombre_completo', 'id_conductor')
                                                : collect()
                                        )
                                        ->searchable()
                                        ->visible(fn ($record) => $record?->requiere_conductor === true),

                                    Forms\Components\Textarea::make('observaciones')
                                        ->label('Observaciones de asignación')
                                        ->rows(3),
                                ]),
                        ]),

                    // 🟨 Devolución (Jefe de Proyecto & Super Admin; estado ASIGNADA)
                    Tabs\Tab::make('Devolución')
                        ->icon('heroicon-m-arrow-uturn-left')
                        ->visible(fn ($record) =>
                            $record
                            && $record->estado === 'ASIGNADA'
                            && Auth::user()->hasAnyRole(['Jefe de Proyecto','Super Admin','Jefe de Control y Monitoreo','Asistente Control y Monitoreo'])
                        )
                        ->schema([
                            Forms\Components\Group::make()
                                ->statePath('devolucion')
                                ->schema([
                                    Forms\Components\FileUpload::make('fotos_evidencia')
                                        ->label('Fotos evidencia')
                                        ->multiple()
                                        ->disk('public')
                                        ->directory('devoluciones/fotos')
                                        ->acceptedFileTypes(['image/*']),

                                    Forms\Components\FileUpload::make('videos_evidencia')
                                        ->label('Videos evidencia')
                                        ->multiple()
                                        ->disk('public')
                                        ->directory('devoluciones/videos')
                                        ->acceptedFileTypes(['video/*']),

                                    Forms\Components\TextInput::make('ubicacion_text')
                                        ->label('Ubicación (texto)')
                                        ->maxLength(250),

                                    Forms\Components\Textarea::make('observaciones')
                                        ->label('Observaciones')
                                        ->rows(3),
                                ]),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_solicitud')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('proyecto.proyecto')
                    ->label('Proyecto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipoVehiculo.nombre')
                    ->label('Tipo')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('solicitante.name')
                    ->label('Solicitante')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->placeholder('Indeterminado')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->color(fn (string $state) => match ($state) {
                        'PENDIENTE'     => 'warning',
                        'ASIGNADA'      => 'success',
                        'EN DEVOLUCIÓN' => 'info',
                        'FINALIZADA'    => 'danger',
                        default         => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_solicitud')
                    ->label('Fecha Solicitud')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'PENDIENTE'     => 'Pendiente',
                        'ASIGNADA'      => 'Asignada',
                        'EN DEVOLUCIÓN' => 'En devolución',
                        'FINALIZADA'    => 'Finalizada',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->estado === 'PENDIENTE' || Auth::user()->hasRole('Super Admin')),
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

    /* Utilidades de fecha (reactivas) */
    private static function calcularFechaFin(?string $inicio, ?int $dias, callable $set): void
    {
        if ($inicio && $dias) {
            $fin = Carbon::parse($inicio)->addDays($dias);
            $set('fecha_fin', $fin->format('Y-m-d'));
        }
    }

    private static function calcularCantidadDias(?string $inicio, ?string $fin, callable $set): void
    {
        if ($inicio && $fin) {
            $set('cantidad_dias', Carbon::parse($inicio)->diffInDays(Carbon::parse($fin)));
        }
    }
}
