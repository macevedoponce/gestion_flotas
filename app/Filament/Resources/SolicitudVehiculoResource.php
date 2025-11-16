<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitudVehiculoResource\Pages;
use App\Models\SolicitudVehiculo;
use App\Models\Proyecto;
use App\Models\TipoVehiculo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Carbon;
use Filament\Forms\Get;

class SolicitudVehiculoResource extends Resource
{
    protected static ?string $model = SolicitudVehiculo::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Gestión Vehicular';
    protected static ?string $label = 'Solicitud de Vehículo';
    protected static ?string $pluralLabel = 'Solicitudes de Vehículo';

    public static function form(Form $form): Form
    {
        return $form->schema(static::formSchemaSolicitud());
    }

    public static function formSchemaSolicitud(): array
    {
        return [

            Forms\Components\Section::make('Datos de la Solicitud')
                ->description('Complete la información necesaria para solicitar un vehículo.')
                ->schema([

                    Forms\Components\Select::make('id_usuario_solicitante')
                        ->label('Solicitante')
                        ->relationship('solicitante', 'name')
                        ->default(fn () => auth()->id())
                        ->disabled(fn ($record) => $record !== null)
                        ->required(),

                    Forms\Components\Select::make('id_proyecto')
                        ->label('Proyecto')
                        ->options(function () {
                            $user = auth()->user();

                            return Proyecto::query()
                                ->when(
                                    !$user->hasRole('Super Admin'),
                                    fn ($q) => $q->where('responsable_id', $user->id)
                                )
                                ->pluck('descripcion', 'id_proyecto');
                        })
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('id_tipo_vehiculo')
                        ->label('Tipo de Vehículo')
                        ->options(
                            TipoVehiculo::orderBy('nombre')->pluck('nombre', 'id_tipo')
                        )
                        ->searchable()
                        ->required()
                        ->columnSpan(2),

                    Forms\Components\Textarea::make('motivo_trabajo')
                        ->label('Motivo del Trabajo')
                        ->rows(2),

                    Forms\Components\TextInput::make('lugar_trabajo')
                        ->label('Lugar de Trabajo')
                        ->required(),

                ])->columns(2),

            Forms\Components\Section::make('Periodo Solicitado')
                ->schema([

                    Forms\Components\Toggle::make('indeterminado')
                        ->label('Periodo Indeterminado')
                        ->default(false)
                        ->live(),

                    Forms\Components\DatePicker::make('fecha_inicio')
                        ->label('Fecha Inicio')
                        ->minDate(today())
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, Get $get) {
                            if (!$get('indeterminado') && $state && $get('cantidad_dias')) {
                                $set(
                                    'fecha_fin',
                                    Carbon::parse($state)
                                        ->addDays((int) $get('cantidad_dias'))
                                        ->format('Y-m-d')
                                );
                            }
                        }),

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
                                    Carbon::parse($get('fecha_inicio'))
                                        ->addDays((int) $state)
                                        ->format('Y-m-d')
                                );
                            }
                        }),

                    Forms\Components\DatePicker::make('fecha_fin')
                        ->label('Fecha Fin')
                        ->hidden(fn (Get $get) => $get('indeterminado')),

                ])->columns(2),

            Forms\Components\Section::make('Conductor')
                ->schema([

                    Forms\Components\Toggle::make('requiere_conductor')
                        ->label('Empresa proveerá conductor')
                        ->default(true)
                        ->live(),

                    Forms\Components\TextInput::make('conductor_externo_nombres')
                        ->label('Nombres del Conductor Externo')
                        ->visible(fn (Get $get) => !$get('requiere_conductor')),

                    Forms\Components\TextInput::make('conductor_externo_dni')
                        ->label('DNI')
                        ->visible(fn (Get $get) => !$get('requiere_conductor')),

                    Forms\Components\TextInput::make('conductor_externo_celular')
                        ->label('Celular')
                        ->visible(fn (Get $get) => !$get('requiere_conductor')),

                    Forms\Components\TextInput::make('conductor_externo_licencia')
                        ->label('Licencia')
                        ->visible(fn (Get $get) => !$get('requiere_conductor')),

                ])->columns(2),

            Forms\Components\Hidden::make('estado')
                ->default('PENDIENTE'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id_solicitud')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('proyecto.descripcion')
                    ->label('Proyecto')
                    ->searchable()
                    ->limit(35),

                Tables\Columns\BadgeColumn::make('tipoVehiculo.nombre')
                    ->label('Tipo')
                    ->colors(['primary']),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->placeholder('Indeterminado')
                    ->date('d/m/Y'),

                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'warning' => 'PENDIENTE',
                        'success' => 'ASIGNADO',
                        'primary' => 'APROBADO',
                        'danger'  => 'RECHAZADO',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('asignar')
                    ->label('Asignar')
                    ->icon('heroicon-o-truck')
                    ->color('primary')
                    ->visible(fn ($record) =>
                        $record->estado === 'PENDIENTE' &&
                        auth()->user()->hasRole('Jefe de Control y Monitoreo')
                    )
                    ->url(fn ($record) => route(
                        'filament.resources.asignaciones-vehiculos.create',
                        ['solicitud' => $record->id_solicitud]
                    )),
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
