<?php

namespace App\Filament\Resources\SolicitudVehiculoResource\Pages;

use App\Filament\Resources\SolicitudVehiculoResource;
use App\Models\AsignacionVehiculo;
use App\Models\Conductor;
use App\Models\SolicitudDevolucion;
use App\Models\Vehiculo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Tabs;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSolicitudVehiculo extends EditRecord
{
    protected static string $resource = SolicitudVehiculoResource::class;

    /**
     * FORMULARIO PRINCIPAL (TABS)
     */
    public function form(Form $form): Form
    {
        return $form->schema([

            Tabs::make('SolicitudProcesoTabs')
                ->tabs([

                    Tabs\Tab::make('Solicitud')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->schema(
                            SolicitudVehiculoResource::formSchemaSolicitud()
                        ),

                    Tabs\Tab::make('Asignación')
                        ->icon('heroicon-o-truck')
                        ->schema($this->getAsignacionSchema())
                        ->visible(fn ($record) =>
                            in_array($record?->estado, ['PENDIENTE', 'ASIGNADO'])
                        ),

                    Tabs\Tab::make('Devolución')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->schema($this->getDevolucionSchema())
                        ->visible(fn ($record) =>
                            in_array($record?->estado, ['ASIGNADO', 'APROBADO'])
                        ),

                    Tabs\Tab::make('Revisión')
                        ->icon('heroicon-o-check-badge')
                        ->schema($this->getRevisionSchema())
                        ->visible(fn ($record) =>
                            in_array($record?->estado, [
                                'DEVOLUCION_SOLICITADA',
                                'RECHAZO_DEVOLUCION',
                                'FINALIZADO'
                            ])
                        ),

                ])
                ->columnSpanFull()
        ]);
    }

    // =============================================================
    // =============== TAB 2 — ESQUEMA PARA ASIGNACIÓN =============
    // =============================================================
    protected function getAsignacionSchema(): array
    {
        return [

            Forms\Components\Section::make('Asignación de Vehículo')
                ->description('Asignar vehículo y conductor disponibles.')
                ->schema([

                    Forms\Components\Select::make('asignacion.id_vehiculo')
                        ->label('Vehículo disponible')
                        ->required()
                        ->options(function () {
                            $record = $this->record;

                            return Vehiculo::query()
                                ->where('estado', 'DISPONIBLE')
                                ->where('id_tipo_vehiculo', $record->id_tipo_vehiculo)
                                ->orderBy('placa')
                                ->pluck('placa', 'id_vehiculo');
                        })
                        ->searchable()
                        ->helperText('Solo vehículos DISPONIBLES del tipo solicitado.'),

                    Forms\Components\Select::make('asignacion.id_conductor')
                        ->label('Conductor')
                        ->required()
                        ->options(function () {
                            return Conductor::where('estado_disponibilidad', 'DISPONIBLE')
                                ->orderBy('nombre_completo')
                                ->pluck('nombre_completo', 'id_conductor');
                        })
                        ->visible(fn () => $this->record->requiere_conductor),

                    Forms\Components\Placeholder::make('infoConductorExterno')
                        ->label('Conductor externo registrado')
                        ->content(function ($record) {
                            if ($record->requiere_conductor) return '';

                            return "
                                <b>{$record->conductor_externo_nombres}</b><br>
                                DNI: {$record->conductor_externo_dni}<br>
                                Cel: {$record->conductor_externo_celular}<br>
                                Licencia: {$record->conductor_externo_licencia}
                            ";
                        })
                        ->visible(fn () => !$this->record->requiere_conductor),

                    Forms\Components\Textarea::make('asignacion.observaciones')
                        ->label('Observaciones'),
                ]),

            Forms\Components\Actions::make([
                Forms\Components\Actions\Action::make('asignarVehiculo')
                    ->label('Asignar vehículo')
                    ->icon('heroicon-o-check')
                    ->color('primary')
                    ->action(fn () => $this->procesarAsignacion()),
            ])
        ];
    }

    // =============================================================
    // =============== PROCESO DE ASIGNACIÓN ======================
    // =============================================================
    private function procesarAsignacion()
    {
        $record = $this->record;
        $data = $this->form->getState()['asignacion'] ?? null;

        if (!$data) {
            Notification::make()
                ->title('Debes completar la información de asignación.')
                ->danger()
                ->send();
            return;
        }

        // Si NO requiere conductor → crear uno externo
        if (!$record->requiere_conductor) {

            $conductor = Conductor::create([
                'nombre_completo' => $record->conductor_externo_nombres,
                'documento_identidad' => $record->conductor_externo_dni,
                'celular' => $record->conductor_externo_celular,
                'licencia_numero' => $record->conductor_externo_licencia,
                'estado_disponibilidad' => 'DISPONIBLE',
            ]);

            $data['id_conductor'] = $conductor->id_conductor;
        }

        // Crear asignación
        AsignacionVehiculo::create([
            'id_solicitud' => $record->id_solicitud,
            'id_proyecto' => $record->id_proyecto,
            'id_vehiculo' => $data['id_vehiculo'],
            'id_conductor' => $data['id_conductor'],
            'id_jefe_control' => auth()->id(),
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        // Cambiar estados
        $record->update(['estado' => 'ASIGNADO']);

        Vehiculo::where('id_vehiculo', $data['id_vehiculo'])
            ->update(['estado' => 'ASIGNADO']);

        Conductor::where('id_conductor', $data['id_conductor'])
            ->update(['estado_disponibilidad' => 'OCUPADO']);

        Notification::make()
            ->title('Vehículo asignado correctamente.')
            ->success()
            ->send();
    }

    // =============================================================
    // =============== TAB 3 — DEVOLUCIÓN ==========================
    // =============================================================
    protected function getDevolucionSchema(): array
    {
        return [

            Forms\Components\Section::make('Solicitud de Devolución')
                ->description('Subir evidencias y registrar entrega.')
                ->schema([

                    Forms\Components\FileUpload::make('devolucion.fotos_evidencia')
                        ->label('Fotos')
                        ->multiple()
                        ->image()
                        ->directory('devoluciones/fotos'),

                    Forms\Components\FileUpload::make('devolucion.videos_evidencia')
                        ->label('Videos')
                        ->multiple()
                        ->directory('devoluciones/videos'),

                    Forms\Components\TextInput::make('devolucion.ubicacion_text')
                        ->label('Ubicación'),

                    Forms\Components\Textarea::make('devolucion.observaciones')
                        ->label('Observaciones'),
                ]),

            Forms\Components\Actions::make([
                Forms\Components\Actions\Action::make('solicitarDevolucion')
                    ->label('Enviar solicitud de devolución')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->action(fn () => $this->procesarDevolucion()),
            ])
        ];
    }

    private function procesarDevolucion()
    {
        $record = $this->record;
        $data = $this->form->getState()['devolucion'] ?? null;

        if (!$data) {
            Notification::make()
                ->title('Debes completar los datos de devolución.')
                ->danger()
                ->send();
            return;
        }

        SolicitudDevolucion::create([
            'id_asignacion' => $record->asignacion?->id_asignacion,
            'id_usuario_solicitante' => auth()->id(),
            'fotos_evidencia' => $data['fotos_evidencia'] ?? [],
            'videos_evidencia' => $data['videos_evidencia'] ?? [],
            'ubicacion_text' => $data['ubicacion_text'] ?? null,
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        $record->update(['estado' => 'DEVOLUCION_SOLICITADA']);

        Notification::make()
            ->title('Solicitud de devolución enviada.')
            ->success()
            ->send();
    }

    // =============================================================
    // =============== TAB 4 — REVISIÓN ============================
    // =============================================================
    protected function getRevisionSchema(): array
    {
        return [

            Forms\Components\Section::make('Revisión de Devolución')
                ->description('Validación final por Control y Monitoreo.')
                ->schema([

                    Forms\Components\Placeholder::make('fotos')
                        ->label('Fotos enviadas')
                        ->content(fn ($record) =>
                            json_encode($record->devolucion?->fotos_evidencia ?? [])
                        ),

                    Forms\Components\Placeholder::make('videos')
                        ->label('Videos enviados')
                        ->content(fn ($record) =>
                            json_encode($record->devolucion?->videos_evidencia ?? [])
                        ),

                    Forms\Components\Textarea::make('revision.comentarios_revision')
                        ->label('Comentarios del revisor')
                        ->rows(3)
                        ->visible(fn ($record) =>
                            in_array($record->estado, ['DEVOLUCION_SOLICITADA'])
                        ),

                    Forms\Components\Select::make('revision.accion')
                        ->label('Acción')
                        ->options([
                            'APROBAR' => 'Aprobar devolución',
                            'RECHAZAR' => 'Rechazar devolución',
                        ])
                        ->required()
                        ->visible(fn ($record) =>
                            $record->estado === 'DEVOLUCION_SOLICITADA'
                        ),
                ]),

            Forms\Components\Actions::make([
                Forms\Components\Actions\Action::make('procesarRevision')
                    ->label('Registrar revisión')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn () =>
                        $this->record->estado === 'DEVOLUCION_SOLICITADA'
                    )
                    ->action(fn () => $this->procesarRevision()),
            ])
        ];
    }

    private function procesarRevision()
    {
        $record = $this->record;
        $rev = $this->form->getState()['revision'] ?? null;

        if (!$rev) {
            Notification::make()->title('Faltan datos')->danger()->send();
            return;
        }

        $devolucion = $record->devolucion;

        $devolucion->update([
            'comentarios_revision' => $rev['comentarios_revision'] ?? null,
            'validado_por' => auth()->id(),
            'fecha_revision' => now(),
        ]);

        // ===========================================
        // RECHAZO
        // ===========================================
        if ($rev['accion'] === 'RECHAZAR') {
            $record->update(['estado' => 'RECHAZO_DEVOLUCION']);

            Notification::make()
                ->title('Devolución rechazada.')
                ->danger()
                ->send();
            return;
        }

        // ===========================================
        // APROBACIÓN FINAL
        // ===========================================
        if ($rev['accion'] === 'APROBAR') {

            // Liberar vehículo
            Vehiculo::where('id_vehiculo', $record->asignacion->id_vehiculo)
                ->update(['estado' => 'DISPONIBLE']);

            // Liberar conductor
            Conductor::where('id_conductor', $record->asignacion->id_conductor)
                ->update(['estado_disponibilidad' => 'DISPONIBLE']);

            // Finalizar asignación
            $record->asignacion->update([
                'estado' => 'FINALIZADA',
                'fecha_finalizacion' => now()
            ]);

            $record->update(['estado' => 'FINALIZADO']);

            Notification::make()
                ->title('Solicitud finalizada correctamente.')
                ->success()
                ->send();
        }
    }
}
