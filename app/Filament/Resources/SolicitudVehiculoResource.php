<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitudVehiculoResource\Pages;
use App\Models\SolicitudVehiculo;
use App\Models\Proyecto;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use App\Models\Conductor;
use App\Models\AsignacionVehiculo;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;


class SolicitudVehiculoResource extends Resource
{
    protected static ?string $model = SolicitudVehiculo::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Gestión Vehicular';
    protected static ?string $modelLabel = 'Solicitud de Vehículo';
    protected static ?string $pluralModelLabel = 'Solicitudes de Vehículo';

    // ======================================================================
    // QUIÉN VE ESTE MÓDULO
    // ======================================================================
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() &&
            auth()->user()->hasAnyRole([
                'Super Admin',
                'Jefe de Proyecto',
                'Jefe de Control y Monitoreo',
            ]);
    }

    // ======================================================================
    // FORMULARIO PRINCIPAL PARA CREAR / EDITAR SOLICITUDES
    // ======================================================================
    public static function form(Form $form): Form
    {
        return $form->schema(static::schemaSolicitud());
    }

    public static function schemaSolicitud(): array
    {
        return [

            Forms\Components\Section::make('Datos de la Solicitud')
                ->schema([

                    Forms\Components\Select::make('id_usuario_solicitante')
                        ->label('Solicitante')
                        ->relationship('solicitante', 'name')
                        ->default(fn () => auth()->id())
                        ->required()
                        ->disabled(fn ($record) => $record !== null),

                    Forms\Components\Select::make('id_proyecto')
                        ->label('Proyecto')
                        ->options(function () {
                            $user = auth()->user();

                            if ($user->hasRole('Jefe de Proyecto')) {
                                return Proyecto::where('responsable_id', $user->id)
                                    ->pluck('descripcion', 'id_proyecto');
                            }

                            return Proyecto::pluck('descripcion', 'id_proyecto');
                        })
                        ->required()
                        ->searchable()
                        ->dehydrated(),

                    Forms\Components\Select::make('id_tipo_vehiculo')
                        ->label('Tipo de vehículo')
                        ->options(
                            TipoVehiculo::orderBy('nombre')->pluck('nombre', 'id_tipo')
                        )
                        ->required()
                        ->searchable()
                        ->dehydrated(),

                    Forms\Components\Textarea::make('motivo_trabajo')
                        ->label('Motivo')
                        ->rows(2)
                        ->dehydrated(),

                    Forms\Components\TextInput::make('lugar_trabajo')
                        ->label('Lugar de trabajo')
                        ->required()
                        ->dehydrated(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Periodo solicitado')
                ->schema([

                    Forms\Components\Toggle::make('indeterminado')
                        ->label('Indeterminado')
                        ->default(false)
                        ->live()
                        ->dehydrated(),

                    Forms\Components\DatePicker::make('fecha_inicio')
                        ->label('Fecha inicio')
                        ->required()
                        ->minDate(today())
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, Get $get) {
                            if (!$get('indeterminado') && $state && $get('cantidad_dias')) {
                                $set(
                                    'fecha_fin',
                                    Carbon::parse($state)->addDays((int) $get('cantidad_dias'))->format('Y-m-d')
                                );
                            }
                        })
                        ->dehydrated(),

                    Forms\Components\TextInput::make('cantidad_dias')
                        ->label('Días')
                        ->numeric()
                        ->minValue(1)
                        ->live()
                        ->hidden(fn (Get $get) => $get('indeterminado'))
                        ->afterStateUpdated(function ($state, callable $set, Get $get) {
                            if (!$get('indeterminado') && $state && $get('fecha_inicio')) {
                                $set(
                                    'fecha_fin',
                                    Carbon::parse($get('fecha_inicio'))->addDays((int) $state)->format('Y-m-d')
                                );
                            }
                        })
                        ->dehydrated(fn (Get $get) => !$get('indeterminado')),

                    Forms\Components\DatePicker::make('fecha_fin')
                        ->label('Fecha fin')
                        ->hidden(fn (Get $get) => $get('indeterminado'))
                        ->dehydrated(fn (Get $get) => !$get('indeterminado')),
                ])
                ->columns(2),

            Forms\Components\Section::make('Conductor')
                ->schema([

                    Forms\Components\Toggle::make('requiere_conductor')
                        ->label('Empresa proveerá conductor')
                        ->default(true)
                        ->live()
                        ->dehydrated(),

                    Forms\Components\TextInput::make('conductor_externo_nombres')
                        ->label('Nombre conductor externo')
                        ->visible(fn (Get $get) => !$get('requiere_conductor'))
                        ->dehydrated(fn (Get $get) => !$get('requiere_conductor')),

                    Forms\Components\TextInput::make('conductor_externo_dni')
                        ->label('DNI')
                        ->visible(fn (Get $get) => !$get('requiere_conductor'))
                        ->dehydrated(fn (Get $get) => !$get('requiere_conductor')),

                    Forms\Components\TextInput::make('conductor_externo_celular')
                        ->label('Celular')
                        ->visible(fn (Get $get) => !$get('requiere_conductor'))
                        ->dehydrated(fn (Get $get) => !$get('requiere_conductor')),

                    Forms\Components\TextInput::make('conductor_externo_licencia')
                        ->label('Licencia')
                        ->visible(fn (Get $get) => !$get('requiere_conductor'))
                        ->dehydrated(fn (Get $get) => !$get('requiere_conductor')),
                ])
                ->columns(2),

            Forms\Components\Hidden::make('estado')
                ->default('PENDIENTE'),
        ];
    }

    // ======================================================================
    // TABLA + ACCIONES
    // ======================================================================
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id_solicitud')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('solicitante.name')
                    ->label('Solicitante'),

                Tables\Columns\TextColumn::make('proyecto.descripcion')
                    ->label('Proyecto')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\BadgeColumn::make('tipoVehiculo.nombre')
                    ->label('Tipo'),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->placeholder('Indeterminado'),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'PENDIENTE',
                        'primary' => 'APROBADO',
                        'success' => 'ASIGNADO',
                        'danger'  => 'RECHAZADO',
                    ]),
            ])
            ->defaultSort('id_solicitud', 'desc')

            ->actions([

                // ======================================================
                // EDITAR SOLICITUD (solo pendiente)
                // ======================================================
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) =>
                        $record->estado === 'PENDIENTE' &&
                        auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Proyecto'])
                    ),

                // ======================================================
                // APROBAR
                // ======================================================
                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn ($record) =>
                        $record->estado === 'PENDIENTE' &&
                        auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Control y Monitoreo'])
                    )
                    ->action(fn ($record) => $record->update(['estado' => 'APROBADO'])),

                // ======================================================
                // RECHAZAR
                // ======================================================
                Tables\Actions\Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) =>
                        $record->estado === 'PENDIENTE' &&
                        auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Control y Monitoreo'])
                    )
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['estado' => 'RECHAZADO'])),

                // ======================================================
                // ASIGNAR VEHÍCULO + CONDUCTOR (con validaciones)
                // ======================================================
                Tables\Actions\Action::make('asignar')
                    ->label('Asignar Vehículo')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->visible(fn ($record) =>
                        $record->estado === 'APROBADO' &&
                        auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Control y Monitoreo'])
                    )
                    ->form([

                         Forms\Components\View::make('filament.solicitudes.partials.conductor-externo')
                            ->visible(fn ($record) => ! $record->requiere_conductor),

                        // ======================================================
                        // VALIDACIÓN DE VEHÍCULOS DISPONIBLES
                        // ======================================================
                        Forms\Components\Select::make('id_vehiculo')
                            ->label('Vehículo disponible')
                            ->options(function ($record) {

                                $baseQuery = Vehiculo::where('estado', 'DISPONIBLE')
                                    ->where('id_tipo_vehiculo', $record->id_tipo_vehiculo);

                                $count = $baseQuery->count();

                                // Si no hay vehículos del tipo solicitado:
                                if ($count === 0) {

                                    // Super Admin → modo flexible
                                    if (auth()->user()->hasRole('Super Admin')) {
                                        return Vehiculo::where('estado', 'DISPONIBLE')
                                            ->pluck('placa', 'id_vehiculo');
                                    }

                                    // Usuarios normales → vacío
                                    return [];
                                }

                                return $baseQuery->pluck('placa', 'id_vehiculo');
                            })
                            ->helperText(function ($record) {

                                $count = Vehiculo::where('estado', 'DISPONIBLE')
                                    ->where('id_tipo_vehiculo', $record->id_tipo_vehiculo)
                                    ->count();

                                if ($count === 0 && !auth()->user()->hasRole('Super Admin')) {
                                    return 'No hay vehículos DISPONIBLES del tipo solicitado.';
                                }

                                if ($count === 0 && auth()->user()->hasRole('Super Admin')) {
                                    return 'Modo flexible: no hay vehículos del tipo solicitado, mostrando todos los disponibles.';
                                }

                                return null;
                            })
                            ->required()
                            ->searchable(),

                        // ======================================================
                        // VALIDACIÓN DE CONDUCTORES DISPONIBLES
                        // ======================================================
                        Forms\Components\Select::make('id_conductor')
                            ->label('Conductor disponible')
                            ->visible(fn ($record) => $record->requiere_conductor)
                            ->options(function ($record) {

                                $query = Conductor::where('estado_disponibilidad', 'DISPONIBLE');

                                $count = $query->count();

                                if ($count === 0 && !auth()->user()->hasRole('Super Admin')) {
                                    return [];
                                }

                                return $query->pluck('nombre_completo', 'id_conductor');
                            })
                            ->helperText(function ($record) {

                                $count = Conductor::where('estado_disponibilidad', 'DISPONIBLE')
                                    ->count();

                                if ($count === 0 && !auth()->user()->hasRole('Super Admin')) {
                                    return 'No hay conductores disponibles.';
                                }

                                if ($count === 0 && auth()->user()->hasRole('Super Admin')) {
                                    return 'Modo flexible: mostrando todos los conductores aunque no haya disponibles.';
                                }

                                return null;
                            })
                            ->required(fn ($record) => $record->requiere_conductor)
                            ->searchable(),

                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones'),
                    ])

                    ->action(function ($record, array $data) {

                        // 1. Determinar conductor final
                        if ($record->requiere_conductor) {
                            $idConductor = $data['id_conductor'] ?? null;
                        } else {
                            // Crear EXTERNO
                            $conductor = app(\App\Services\ConductorService::class)
                                            ->crearDesdeSolicitud($record);

                            $idConductor = $conductor->id_conductor;
                        }

                        // 2. Crear asignación
                        $asignacion = AsignacionVehiculo::create([
                            'id_solicitud'    => $record->id_solicitud,
                            'id_proyecto'     => $record->id_proyecto,
                            'id_vehiculo'     => $data['id_vehiculo'],
                            'id_conductor'    => $idConductor,
                            'id_jefe_control' => auth()->id(),
                            'estado'          => 'ASIGNADO',
                            'observaciones'   => $data['observaciones'] ?? null,
                        ]);

                        // 3. Marcar vehículo como ocupado
                        Vehiculo::where('id_vehiculo', $data['id_vehiculo'])
                            ->update(['estado' => 'ASIGNADO']);

                        // 4. Marcar conductor como ocupado
                        if ($idConductor) {
                            Conductor::where('id_conductor', $idConductor)
                                ->update(['estado_disponibilidad' => 'OCUPADO']);
                        }

                        // 5. Cambiar estado de solicitud
                        $record->update(['estado' => 'ASIGNADO']);

                        Notification::make()
                            ->title('Vehículo asignado correctamente')
                            ->success()
                            ->send();
                    })

            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSolicitudVehiculos::route('/'),
            'create' => Pages\CreateSolicitudVehiculo::route('/create'),
            'edit'   => Pages\EditSolicitudVehiculo::route('/{record}/edit'),
        ];
    }
}
