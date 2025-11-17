<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitudDevolucionResource\Pages;
use App\Models\SolicitudDevolucion;
use App\Models\AsignacionLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;

class SolicitudDevolucionResource extends Resource
{
    protected static ?string $model = SolicitudDevolucion::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'Gestión Vehicular';
    protected static ?string $modelLabel = 'Devolución de Vehículo';
    protected static ?string $pluralModelLabel = 'Devoluciones de Vehículos';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check()
            && auth()->user()->hasAnyRole([
                'Super Admin',
                'Jefe de Proyecto',
                'Jefe de Control y Monitoreo',
            ]);
    }

    // ---------------------------------------------------------------------
    // FORM: vista principalmente de lectura (el flujo se maneja con actions)
    // ---------------------------------------------------------------------
    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Datos generales')
                ->schema([
                    Forms\Components\Placeholder::make('id_devolucion')
                        ->label('ID Devolución')
                        ->content(fn ($record) => $record?->id_devolucion),

                    Forms\Components\Placeholder::make('proyecto')
                        ->label('Proyecto')
                        ->content(fn ($record) =>
                            $record?->asignacion?->proyecto?->descripcion ?? '-'
                        ),

                    Forms\Components\Placeholder::make('vehiculo')
                        ->label('Vehículo')
                        ->content(fn ($record) =>
                            $record?->asignacion?->vehiculo?->placa ?? '-'
                        ),

                    Forms\Components\Placeholder::make('conductor')
                        ->label('Conductor')
                        ->content(fn ($record) =>
                            $record?->asignacion?->conductor?->nombre_completo ?? '-'
                        ),

                    Forms\Components\Placeholder::make('solicitante')
                        ->label('Solicitante')
                        ->content(fn ($record) =>
                            $record?->solicitante?->name ?? '-'
                        ),

                    Forms\Components\Placeholder::make('estado')
                        ->label('Estado')
                        ->content(fn ($record) =>
                            $record?->estado ?? '-'
                        ),
                ])
                ->columns(2),

            Forms\Components\Section::make('Evidencias del Conductor')
                ->schema([
                    Forms\Components\FileUpload::make('evidencias_conductor')
                        ->label('Evidencias del conductor')
                        ->multiple()
                        ->image()
                        ->openable()
                        ->downloadable()
                        ->disabled(),

                    Forms\Components\Textarea::make('observaciones_conductor')
                        ->label('Observaciones del conductor')
                        ->rows(3)
                        ->disabled(),

                    Forms\Components\TextInput::make('ubicacion_text')
                        ->label('Ubicación de entrega')
                        ->disabled(),
                ]),

            Forms\Components\Section::make('Validación del Jefe de Proyecto')
                ->schema([
                    Forms\Components\Placeholder::make('validado_por_proyecto')
                        ->label('Validado por proyecto')
                        ->content(fn ($record) =>
                            $record?->validadorProyecto?->name ?? '-'
                        ),

                    Forms\Components\Placeholder::make('fecha_validacion_proyecto')
                        ->label('Fecha validación proyecto')
                        ->content(fn ($record) =>
                            $record?->fecha_validacion_proyecto?->format('d/m/Y H:i') ?? '-'
                        ),

                    Forms\Components\Textarea::make('comentarios_validacion_proyecto')
                        ->label('Comentarios del jefe de proyecto')
                        ->rows(3)
                        ->disabled(),
                ]),

            Forms\Components\Section::make('Revisión final de Control')
                ->schema([
                    Forms\Components\Placeholder::make('validado_por')
                        ->label('Validado por control')
                        ->content(fn ($record) =>
                            $record?->validadorFinal?->name ?? '-'
                        ),

                    Forms\Components\Placeholder::make('fecha_revision')
                        ->label('Fecha de revisión')
                        ->content(fn ($record) =>
                            $record?->fecha_revision?->format('d/m/Y H:i') ?? '-'
                        ),

                    Forms\Components\Textarea::make('comentarios_revision')
                        ->label('Comentarios de control')
                        ->rows(3)
                        ->disabled(),
                ]),
        ]);
    }

    // ---------------------------------------------------------------------
    // TABLA: vista de devoluciones + ACCIONES por rol/estado
    // ---------------------------------------------------------------------
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id_devolucion')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('asignacion.id_asignacion')
                    ->label('Asignación')
                    ->sortable(),

                Tables\Columns\TextColumn::make('asignacion.proyecto.descripcion')
                    ->label('Proyecto')
                    ->limit(40),

                Tables\Columns\TextColumn::make('asignacion.vehiculo.placa')
                    ->label('Vehículo'),

                Tables\Columns\TextColumn::make('asignacion.conductor.nombre_completo')
                    ->label('Conductor')
                    ->limit(30),

                Tables\Columns\TextColumn::make('solicitante.name')
                    ->label('Solicitante'),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'PENDIENTE_EVIDENCIAS_CONDUCTOR',
                        'info'    => 'EVIDENCIAS_ENVIADAS',
                        'primary' => 'ENVIADO_A_CONTROL',
                        'danger'  => 'RECHAZADO_POR_CONTROL',
                        'success' => 'APROBADA',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'PENDIENTE_EVIDENCIAS_CONDUCTOR' => 'Pend. evidencias',
                            'EVIDENCIAS_ENVIADAS'           => 'Evidencias enviadas',
                            'ENVIADO_A_CONTROL'             => 'Enviado a Control',
                            'RECHAZADO_POR_CONTROL'         => 'Rechazado por Control',
                            'APROBADA'                      => 'Aprobada',
                            default                         => $state,
                        };
                    }),

                Tables\Columns\TextColumn::make('fecha_solicitud')
                    ->label('Fecha solicitud')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->defaultSort('id_devolucion', 'desc')
            ->actions([

                Tables\Actions\ViewAction::make(),

                // ==========================================================
                // 1. VALIDAR EVIDENCIAS Y ENVIAR A CONTROL (JEFE PROYECTO)
                // ==========================================================
                Tables\Actions\Action::make('validarProyecto')
                    ->label('Validar y enviar a Control')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->visible(fn ($record) =>
                        auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Proyecto']) &&
                        $record->estado === 'EVIDENCIAS_ENVIADAS'
                    )
                    ->form([
                        Forms\Components\Textarea::make('comentarios')
                            ->label('Comentarios del Jefe de Proyecto')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function (SolicitudDevolucion $record, array $data) {

                        $record->update([
                            'validado_por_proyecto' => auth()->id(),
                            'fecha_validacion_proyecto' => now(),
                            'comentarios_validacion_proyecto' => $data['comentarios'],
                            'estado' => 'ENVIADO_A_CONTROL',
                        ]);

                        // Registrar en bitácora de la ASIGNACIÓN
                        if ($record->asignacion) {
                            AsignacionLog::create([
                                'id_asignacion' => $record->asignacion->id_asignacion,
                                'id_usuario' => auth()->id(),
                                'accion' => 'Devolución validada por Jefe de Proyecto',
                                'detalles' => [
                                    'comentarios' => $data['comentarios'],
                                ],
                            ]);
                        }

                        Notification::make()
                            ->title('Devolución enviada a Control.')
                            ->success()
                            ->send();
                    }),

                // ==========================================================
                // 2. APROBAR DEVOLUCIÓN (JEFE CONTROL)
                // ==========================================================
                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) =>
                        auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Control y Monitoreo']) &&
                        $record->estado === 'ENVIADO_A_CONTROL'
                    )
                    ->form([
                        Forms\Components\Textarea::make('comentarios')
                            ->label('Comentarios de aprobación')
                            ->rows(3),
                    ])
                    ->requiresConfirmation()
                    ->action(function (SolicitudDevolucion $record, array $data) {

                        $asignacion = $record->asignacion;
                        $vehiculo = $asignacion?->vehiculo;
                        $conductor = $asignacion?->conductor;

                        // Liberar vehículo y conductor
                        if ($vehiculo) {
                            $vehiculo->update(['estado' => 'DISPONIBLE']);
                        }
                        if ($conductor) {
                            $conductor->update(['estado_disponibilidad' => 'DISPONIBLE']);
                        }

                        // Finalizar asignación
                        if ($asignacion) {
                            $asignacion->update([
                                'estado' => 'FINALIZADA',
                                'fecha_finalizacion' => now(),
                            ]);
                        }

                        // Actualizar devolución
                        $record->update([
                            'validado_por' => auth()->id(),
                            'fecha_revision' => now(),
                            'comentarios_revision' => $data['comentarios'] ?? null,
                            'estado' => 'APROBADA',
                        ]);

                        // Bitácora
                        if ($asignacion) {
                            AsignacionLog::create([
                                'id_asignacion' => $asignacion->id_asignacion,
                                'id_usuario' => auth()->id(),
                                'accion' => 'Devolución aprobada por Control',
                                'detalles' => [
                                    'comentarios' => $data['comentarios'] ?? null,
                                ],
                            ]);
                        }

                        Notification::make()
                            ->title('Devolución aprobada y asignación finalizada.')
                            ->success()
                            ->send();
                    }),

                // ==========================================================
                // 3. RECHAZAR DEVOLUCIÓN (JEFE CONTROL)
                // ==========================================================
                Tables\Actions\Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) =>
                        auth()->user()->hasAnyRole(['Super Admin', 'Jefe de Control y Monitoreo']) &&
                        $record->estado === 'ENVIADO_A_CONTROL'
                    )
                    ->form([
                        Forms\Components\Textarea::make('motivo')
                            ->label('Motivo del rechazo')
                            ->rows(3)
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->action(function (SolicitudDevolucion $record, array $data) {

                        $record->update([
                            'validado_por' => auth()->id(),
                            'fecha_revision' => now(),
                            'comentarios_revision' => $data['motivo'],
                            'estado' => 'RECHAZADO_POR_CONTROL',
                        ]);

                        if ($record->asignacion) {
                            AsignacionLog::create([
                                'id_asignacion' => $record->asignacion->id_asignacion,
                                'id_usuario' => auth()->id(),
                                'accion' => 'Devolución rechazada por Control',
                                'detalles' => [
                                    'motivo' => $data['motivo'],
                                ],
                            ]);
                        }

                        Notification::make()
                            ->title('Devolución rechazada por Control.')
                            ->danger()
                            ->send();
                    }),

                // ==========================================================
                // 4. VER BITÁCORA (DE LA ASIGNACIÓN RELACIONADA)
                // ==========================================================
                Tables\Actions\Action::make('verBitacora')
                    ->label('Bitácora')
                    ->icon('heroicon-o-clock')
                    ->modalHeading('Bitácora de la asignación asociada')
                    ->modalSubmitAction(false)
                    ->modalWidth('3xl')
                    ->visible(fn ($record) => $record->asignacion !== null)
                    ->modalContent(function ($record) {

                        $asignacion = $record->asignacion;

                        if (!$asignacion || $asignacion->logs->isEmpty()) {
                            return new HtmlString("
                                <div class='p-6 text-center text-gray-600 dark:text-gray-300'>
                                    No existen registros en la bitácora.
                                </div>
                            ");
                        }

                        $html = "<div class='space-y-6 px-1'>";

                        foreach ($asignacion->logs as $log) {

                            $usuario = $log->usuario?->name ?? 'Sistema';
                            $fecha = $log->created_at->format('d/m/Y H:i');

                            $det = $log->detalles ?? [];
                            $antes = $det['anterior'] ?? '—';
                            $despues = $det['nuevo'] ?? '—';
                            $motivo = $det['motivo'] ?? ($det['comentarios'] ?? '—');

                            $icon = match ($log->accion) {
                                'Cambio de conductor'           => '👤',
                                'Reasignación de vehículo'      => '🚗',
                                'Devolución aprobada por Control',
                                'Devolución validada por Jefe de Proyecto',
                                'Devolución rechazada por Control' => '📝',
                                default => '📝',
                            };

                            $badgeColor = match ($log->accion) {
                                'Cambio de conductor'           => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'Reasignación de vehículo'      => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'Devolución aprobada por Control' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'Devolución rechazada por Control' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                            };

                            $html .= "
                            <div class='relative border-l-2 border-gray-300 dark:border-gray-600 pl-6'>
                                <div class='absolute -left-[10px] top-2 w-4 h-4 rounded-full bg-white dark:bg-gray-800 border-2 border-primary-500'></div>

                                <div class='p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700
                                            bg-white dark:bg-gray-800'>

                                    <div class='flex items-center justify-between'>
                                        <span class='text-base font-semibold flex items-center gap-2'>
                                            <span class='text-lg'>$icon</span> {$log->accion}
                                        </span>

                                        <span class='text-xs px-2 py-1 rounded-md $badgeColor'>
                                            $fecha
                                        </span>
                                    </div>

                                    <div class='mt-4 grid grid-cols-2 gap-4'>
                                        <div>
                                            <div class='text-xs text-gray-500 dark:text-gray-400 uppercase mb-1 font-bold'>
                                                Antes
                                            </div>
                                            <div class='p-2 rounded-lg bg-gray-100 dark:bg-gray-700
                                                        text-gray-800 dark:text-gray-200'>
                                                $antes
                                            </div>
                                        </div>

                                        <div>
                                            <div class='text-xs text-gray-500 dark:text-gray-400 uppercase mb-1 font-bold'>
                                                Después
                                            </div>
                                            <div class='p-2 rounded-lg bg-gray-100 dark:bg-gray-700
                                                        text-gray-800 dark:text-gray-200'>
                                                $despues
                                            </div>
                                        </div>
                                    </div>

                                    <div class='mt-4'>
                                        <div class='text-xs text-gray-500 dark:text-gray-400 uppercase mb-1 font-bold'>
                                            Motivo / Comentario
                                        </div>
                                        <div class='p-2 rounded-lg bg-gray-50 dark:bg-gray-700
                                                    text-gray-700 dark:text-gray-200'>
                                            $motivo
                                        </div>
                                    </div>

                                    <div class='mt-4 text-xs text-gray-500 dark:text-gray-400'>
                                        Registrado por:
                                        <span class='font-semibold text-gray-700 dark:text-gray-200'>$usuario</span>
                                    </div>

                                </div>
                            </div>";
                        }

                        $html .= "</div>";

                        return new HtmlString($html);
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
            'index' => Pages\ListSolicitudDevolucions::route('/'),
            //'view'  => Pages\ViewSolicitudDevolucion::route('/{record}'),
            'edit'  => Pages\EditSolicitudDevolucion::route('/{record}/edit'),
            // No usamos create: las devoluciones se crean desde otros flujos
        ];
    }
}
