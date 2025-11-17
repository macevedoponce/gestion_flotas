<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsignacionVehiculoResource\Pages;
use App\Models\AsignacionVehiculo;
use App\Models\AsignacionLog;
use App\Models\SolicitudDevolucion;
use App\Models\Vehiculo;
use App\Models\Conductor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AsignacionVehiculoResource extends Resource
{
    protected static ?string $model = AsignacionVehiculo::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Gestión Vehicular';
    protected static ?string $navigationLabel = 'Asignaciones de Vehículos';
    protected static ?string $modelLabel = 'Asignación de Vehículo';
    protected static ?string $pluralModelLabel = 'Asignaciones de Vehículos';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check()
            && auth()->user()->hasAnyRole([
                'Super Admin',
                'Jefe de Proyecto',
                'Jefe de Control y Monitoreo',
            ]);
    }

    // =====================================================================
    // FORM (solo lectura básica en Edit/View)
    // =====================================================================
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos generales')
                ->schema([
                    Forms\Components\Placeholder::make('id_asignacion')
                        ->label('ID Asignación')
                        ->content(fn ($record) => $record?->id_asignacion),

                    Forms\Components\Placeholder::make('solicitud')
                        ->label('Solicitud')
                        ->content(fn ($record) =>
                            $record?->solicitud
                                ? ('#' . $record->solicitud->id_solicitud)
                                : '-'
                        ),

                    Forms\Components\Placeholder::make('proyecto')
                        ->label('Proyecto')
                        ->content(fn ($record) =>
                            $record?->proyecto?->descripcion ?? '-'
                        ),

                    Forms\Components\Placeholder::make('vehiculo')
                        ->label('Vehículo')
                        ->content(fn ($record) =>
                            $record?->vehiculo?->placa ?? '-'
                        ),

                    Forms\Components\Placeholder::make('conductor')
                        ->label('Conductor')
                        ->content(fn ($record) =>
                            $record?->conductor?->nombre_completo ?? '-'
                        ),

                    Forms\Components\Placeholder::make('estado')
                        ->label('Estado actual')
                        ->content(fn ($record) => $record?->estado ?? '-'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Observaciones')
                ->schema([
                    Forms\Components\Textarea::make('observaciones')
                        ->label('Observaciones generales')
                        ->disabled(),
                ]),
        ]);
    }

    // =====================================================================
    // TABLA PRINCIPAL + ACCIONES
    // =====================================================================
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id_asignacion')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('solicitud.id_solicitud')
                    ->label('Solicitud')
                    ->sortable(),

                Tables\Columns\TextColumn::make('proyecto.descripcion')
                    ->label('Proyecto')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('vehiculo.placa')
                    ->label('Vehículo'),

                Tables\Columns\TextColumn::make('conductor.nombre_completo')
                    ->label('Conductor'),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'secondary' => 'PENDIENTE',
                        'primary'   => 'ASIGNADO',
                        'warning'   => 'RECOJO_ENVIADO',
                        'info'      => 'EN_USO',
                        'danger'    => 'DEVOLUCION_SOLICITADA',
                        'gray'      => 'EN_REVISION',
                        'success'   => 'FINALIZADA',
                    ]),

                Tables\Columns\TextColumn::make('fecha_asignacion')
                    ->label('Fecha asignación')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->defaultSort('id_asignacion', 'desc')

            ->actions([

                // ==================================================
                // VER DETALLE
                // ==================================================
                Tables\Actions\ViewAction::make(),

                // ==================================================
                // EDITAR (Super Admin siempre, Control si no finalizada)
                // ==================================================
                Tables\Actions\EditAction::make()
                    ->visible(fn (AsignacionVehiculo $record) =>
                        auth()->user()->hasRole('Super Admin') ||
                        (
                            $record->estado !== 'FINALIZADA'
                            && auth()->user()->hasRole('Jefe de Control y Monitoreo')
                        )
                    ),

                // ==================================================
                // CAMBIAR CONDUCTOR (cualquier estado excepto FINALIZADA)
                // ==================================================
                Tables\Actions\Action::make('cambiarConductor')
                    ->label('Cambiar conductor')
                    ->icon('heroicon-o-user')
                    ->color('primary')
                    ->visible(fn (AsignacionVehiculo $record) =>
                        $record->estado !== 'FINALIZADA'
                        && auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Control y Monitoreo'])
                    )
                    ->form([
                        Forms\Components\Select::make('nuevo_conductor')
                            ->label('Nuevo conductor')
                            ->options(
                                Conductor::where('estado_disponibilidad', 'DISPONIBLE')
                                    ->orderBy('nombre_completo')
                                    ->pluck('nombre_completo', 'id_conductor')
                            )
                            ->required()
                            ->searchable(),

                        Forms\Components\Textarea::make('motivo')
                            ->label('Motivo del cambio')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function (AsignacionVehiculo $record, array $data) {

                        $conductorAnterior = $record->conductor;

                        // Liberar anterior
                        if ($conductorAnterior) {
                            $conductorAnterior->update(['estado_disponibilidad' => 'DISPONIBLE']);
                        }

                        // Asignar nuevo
                        $nuevoConductor = Conductor::findOrFail($data['nuevo_conductor']);
                        $nuevoConductor->update(['estado_disponibilidad' => 'OCUPADO']);

                        $record->update(['id_conductor' => $nuevoConductor->id_conductor]);

                        // Bitácora
                        AsignacionLog::create([
                            'id_asignacion' => $record->id_asignacion,
                            'id_usuario'    => auth()->id(),
                            'accion'        => 'Cambio de conductor',
                            'detalles'      => [
                                'anterior' => $conductorAnterior?->nombre_completo,
                                'nuevo'    => $nuevoConductor->nombre_completo,
                                'motivo'   => $data['motivo'],
                            ],
                        ]);

                        Notification::make()
                            ->title('Conductor cambiado correctamente')
                            ->success()
                            ->send();
                    }),

                // ==================================================
                // REASIGNAR VEHÍCULO (cualquier estado excepto FINALIZADA)
                // ==================================================
                Tables\Actions\Action::make('reasignarVehiculo')
                    ->label('Reasignar vehículo')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (AsignacionVehiculo $record) =>
                        $record->estado !== 'FINALIZADA'
                        && auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Control y Monitoreo'])
                    )
                    ->form([
                        Forms\Components\Select::make('nuevo_vehiculo')
                            ->label('Nuevo vehículo')
                            ->options(function (AsignacionVehiculo $record) {

                                if (!$record->vehiculo) {
                                    return [];
                                }

                                return Vehiculo::where('estado', 'DISPONIBLE')
                                    ->where('id_tipo_vehiculo', $record->vehiculo->id_tipo_vehiculo)
                                    ->orderBy('placa')
                                    ->pluck('placa', 'id_vehiculo');
                            })
                            ->required()
                            ->searchable(),

                        Forms\Components\Textarea::make('motivo')
                            ->label('Motivo de la reasignación')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function (AsignacionVehiculo $record, array $data) {

                        $vehAnterior = $record->vehiculo;

                        if ($vehAnterior) {
                            $vehAnterior->update(['estado' => 'DISPONIBLE']);
                        }

                        $nuevoVehiculo = Vehiculo::findOrFail($data['nuevo_vehiculo']);
                        $nuevoVehiculo->update(['estado' => 'ASIGNADO']);

                        $record->update(['id_vehiculo' => $nuevoVehiculo->id_vehiculo]);

                        AsignacionLog::create([
                            'id_asignacion' => $record->id_asignacion,
                            'id_usuario'    => auth()->id(),
                            'accion'        => 'Reasignación de vehículo',
                            'detalles'      => [
                                'anterior' => $vehAnterior?->placa,
                                'nuevo'    => $nuevoVehiculo->placa,
                                'motivo'   => $data['motivo'],
                            ],
                        ]);

                        Notification::make()
                            ->title('Vehículo reasignado correctamente')
                            ->success()
                            ->send();
                    }),

                // ==================================================
                // REGISTRAR RECOJO (Jefe de Proyecto + Super Admin)
                // ==================================================
                Tables\Actions\Action::make('recojoUnidad')
    ->label('Recojo de Unidad')
    ->icon('heroicon-o-camera')
    ->color('warning')
    ->visible(fn ($record) =>
        $record->estado === 'ASIGNADO' &&
        auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Proyecto'])
    )
    ->form([

        Forms\Components\FileUpload::make('foto_km')
            ->label('Foto del tablero (KM)')
            ->required()
            ->image()
            ->directory('recojo/km'),

        Forms\Components\FileUpload::make('foto_frontal')
            ->label('Frontal del vehículo')
            ->required()
            ->image()
            ->directory('recojo/frontal'),

        Forms\Components\FileUpload::make('foto_posterior')
            ->label('Posterior del vehículo')
            ->required()
            ->image()
            ->directory('recojo/posterior'),

        Forms\Components\FileUpload::make('foto_lat_izq')
            ->label('Lateral izquierda')
            ->required()
            ->image()
            ->directory('recojo/lateral_izq'),

        Forms\Components\FileUpload::make('foto_lat_der')
            ->label('Lateral derecha')
            ->required()
            ->image()
            ->directory('recojo/lateral_der'),

        Forms\Components\FileUpload::make('fotos_extra')
            ->label('Fotos adicionales (opcional)')
            ->multiple()
            ->image()
            ->directory('recojo/adicional'),

        Forms\Components\Textarea::make('observaciones')
            ->label('Observaciones')
            ->rows(3),

        Forms\Components\TextInput::make('ubicacion_text')
            ->label('Ubicación textual (opcional)')
            ->placeholder('Ej: Estación base Lima - Puerta 3'),

    ])
    ->action(function ($record, array $data) {
        
        // Guardar evidencias
        $record->update([
            'evidencia_foto_km'     => $data['foto_km'],
            'evidencia_foto_frontal'=> $data['foto_frontal'],
            'evidencia_foto_posterior'=> $data['foto_posterior'],
            'evidencia_foto_lat_izq'=> $data['foto_lat_izq'],
            'evidencia_foto_lat_der'=> $data['foto_lat_der'],
            'evidencia_fotos_extra' => $data['fotos_extra'] ?? [],
            'evidencia_observaciones' => $data['observaciones'] ?? null,
            'evidencia_ubicacion_text' => $data['ubicacion_text'] ?? null,
        ]);

        // Cambiar estado
        $record->update([
            'estado' => 'RECOJO_ENVIADO',
        ]);

        Notification::make()
            ->title('Evidencias enviadas correctamente')
            ->success()
            ->send();
    }),

                // ==================================================
                // VALIDAR RECOJO (Control + Super Admin)
                // ==================================================
                Tables\Actions\Action::make('validarRecojo')
    ->label('Validar Recojo')
    ->icon('heroicon-o-check-badge')
    ->color('success')
    ->visible(fn ($record) =>
        $record->estado === 'RECOJO_ENVIADO' &&
        auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Control y Monitoreo'])
    )
    ->form([

    Forms\Components\Section::make('Evidencia del Recojo')
        ->schema([

            Forms\Components\View::make('filament.recojo.fotos-validador')
                ->label('Fotos registradas'),

            Forms\Components\Textarea::make('comentarios')
                ->label('Comentarios del validador')
                ->rows(3),

            Forms\Components\Select::make('accion')
                ->label('Acción')
                ->options([
                    'APROBAR'  => 'Aprobar recojo',
                    'RECHAZAR' => 'Rechazar y solicitar nueva evidencia'
                ])
                ->required(),
        ])
        ->columns(1),
])

    ->action(function ($record, array $data) {

        if ($data['accion'] === 'RECHAZAR') {
            $record->update([
                'estado' => 'ASIGNADO', // vuelve atrás
            ]);

            Notification::make()
                ->title('Recojo rechazado — solicitar nuevas evidencias')
                ->danger()
                ->send();

            return;
        }

        // Si aprueba:
        $record->update([
            'estado' => 'EN_USO',
        ]);

        Notification::make()
            ->title('Recojo aprobado — el vehículo está oficialmente EN USO')
            ->success()
            ->send();
    }),

                // ==================================================
                // SOLICITAR DEVOLUCIÓN (Jefe de Proyecto + Super Admin)
                // ==================================================
                Tables\Actions\Action::make('solicitarDevolucion')
                    ->label('Solicitar devolución')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('danger')
                    ->visible(fn (AsignacionVehiculo $record) =>
                        $record->estado === 'EN_USO'
                        && ! $record->devoluciones()->exists()
                        && auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Proyecto'])
                    )
                    ->form([
                        Forms\Components\Textarea::make('motivo')
                            ->label('Motivo / comentarios')
                            ->rows(3),
                    ])
                    ->requiresConfirmation()
                    ->action(function (AsignacionVehiculo $record, array $data) {

                        SolicitudDevolucion::create([
                            'id_asignacion'          => $record->id_asignacion,
                            'id_usuario_solicitante' => auth()->id(),
                            'estado'                 => 'PENDIENTE_EVIDENCIAS_CONDUCTOR',
                            'comentarios_revision'   => $data['motivo'] ?? null,
                        ]);

                        $estadoAnterior = $record->estado;

                        $record->update([
                            'estado' => 'DEVOLUCION_SOLICITADA',
                        ]);

                        AsignacionLog::create([
                            'id_asignacion' => $record->id_asignacion,
                            'id_usuario'    => auth()->id(),
                            'accion'        => 'Solicitud de devolución creada',
                            'detalles'      => [
                                'estado_anterior' => $estadoAnterior,
                                'estado_nuevo'    => 'DEVOLUCION_SOLICITADA',
                                'motivo'          => $data['motivo'] ?? null,
                            ],
                        ]);

                        Notification::make()
                            ->title('Solicitud de devolución registrada')
                            ->success()
                            ->send();
                    }),

                // ==================================================
                // BITÁCORA (timeline visual)
                // ==================================================
                Tables\Actions\Action::make('bitacora')
                    ->label('Bitácora')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->modalHeading('Historial de la asignación')
                    ->modalSubmitAction(false)
                    ->modalWidth('4xl')
                    ->modalContent(function (AsignacionVehiculo $record) {

                        if ($record->logs->isEmpty()) {
                            return new HtmlString("
                                <div class='p-6 text-center text-gray-600 dark:text-gray-300'>
                                    No existen registros en la bitácora.
                                </div>
                            ");
                        }

                        $html = "<div class='space-y-6 px-1'>";

                        foreach ($record->logs as $log) {

                            $usuario = $log->usuario?->name ?? 'Sistema';
                            $fecha   = $log->created_at->format('d/m/Y H:i');
                            $det     = $log->detalles ?? [];

                            $antes   = $det['anterior'] ?? ($det['estado_anterior'] ?? '—');
                            $despues = $det['nuevo'] ?? ($det['estado_nuevo'] ?? '—');
                            $motivo  = $det['motivo'] ?? ($det['comentarios'] ?? '—');

                            $icon = match ($log->accion) {
                                'Cambio de conductor'              => '👤',
                                'Reasignación de vehículo'         => '🚗',
                                'Evidencias de recojo registradas' => '📸',
                                'Recojo validado'                  => '✅',
                                'Solicitud de devolución creada'   => '↩️',
                                'Corrección administrativa'        => '⚙️',
                                default                            => '📝',
                            };

                            $html .= "
                            <div class='relative border-l-2 border-gray-300 dark:border-gray-600 pl-6'>
                                <div class='absolute -left-[10px] top-2 w-4 h-4 rounded-full bg-white dark:bg-gray-800 border-2 border-primary-500'></div>

                                <div class='p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900'>
                                    <div class='flex items-center justify-between'>
                                        <span class='text-base font-semibold flex items-center gap-2'>
                                            <span class='text-lg'>$icon</span> {$log->accion}
                                        </span>

                                        <span class='text-xs px-2 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200'>
                                            $fecha
                                        </span>
                                    </div>

                                    <div class='mt-4 grid grid-cols-2 gap-4'>
                                        <div>
                                            <div class='text-xs text-gray-500 dark:text-gray-400 uppercase mb-1'>Antes</div>
                                            <div class='p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200'>$antes</div>
                                        </div>
                                        <div>
                                            <div class='text-xs text-gray-500 dark:text-gray-400 uppercase mb-1'>Después</div>
                                            <div class='p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200'>$despues</div>
                                        </div>
                                    </div>

                                    <div class='mt-4'>
                                        <div class='text-xs text-gray-500 dark:text-gray-400 uppercase mb-1'>Motivo / Comentario</div>
                                        <div class='p-2 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-200'>$motivo</div>
                                    </div>

                                    <div class='mt-4 text-xs text-gray-500 dark:text-gray-400'>
                                        Registrado por: <span class='font-semibold'>$usuario</span>
                                    </div>
                                </div>
                            </div>";
                        }

                        $html .= "</div>";

                        return new HtmlString($html);
                    }),

                // ==================================================
                // CORRECCIÓN ADMINISTRATIVA (Super Admin)
                // ==================================================
                Tables\Actions\Action::make('correccion')
                    ->label('Corrección administrativa')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->visible(fn () => auth()->user()->hasRole('Super Admin'))
                    ->form([
                        Forms\Components\Textarea::make('motivo')
                            ->label('Motivo de la corrección')
                            ->rows(3)
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->action(function (AsignacionVehiculo $record, array $data) {

                        AsignacionLog::create([
                            'id_asignacion' => $record->id_asignacion,
                            'id_usuario'    => auth()->id(),
                            'accion'        => 'Corrección administrativa',
                            'detalles'      => [
                                'motivo' => $data['motivo'],
                            ],
                        ]);

                        Notification::make()
                            ->title('Corrección administrativa registrada')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsignacionVehiculos::route('/'),
            'edit'  => Pages\EditAsignacionVehiculo::route('/{record}/edit'),
        ];
    }
}
