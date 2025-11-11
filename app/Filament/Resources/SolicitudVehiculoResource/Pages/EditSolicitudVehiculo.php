<?php

namespace App\Filament\Resources\SolicitudVehiculoResource\Pages;

use App\Filament\Resources\SolicitudVehiculoResource;
use App\Models\AsignacionVehiculo;
use App\Models\SolicitudDevolucion;
use App\Models\Conductor;
use App\Models\Vehiculo;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditSolicitudVehiculo extends EditRecord
{
    protected static string $resource = SolicitudVehiculoResource::class;

    protected function getFormActions(): array
    {
        $actions = [];

        // Asignar (Control/Monitoreo o Super Admin) cuando está PENDIENTE
        if ($this->record->estado === 'PENDIENTE' && Auth::user()->hasAnyRole(['Jefe de Control y Monitoreo','Super Admin'])) {
            $actions[] = Action::make('asignar')
                ->label('Asignar vehículo')
                ->icon('heroicon-m-clipboard-check')
                ->action(function () {
                    $state = $this->form->getState();
                    $data  = $state['asignacion'] ?? null;

                    if (!$data || empty($data['id_vehiculo'])) {
                        Notification::make()->title('Selecciona un vehículo disponible.')->danger()->send();
                        return;
                    }

                    DB::transaction(function () use ($data) {
                        // Crear (o reutilizar) conductor externo si NO requiere conductor interno
                        if (!$this->record->requiere_conductor && $this->record->conductor_externo_dni) {
                            $externo = Conductor::firstOrCreate(
                                ['documento_identidad' => $this->record->conductor_externo_dni],
                                [
                                    'nombre_completo'       => $this->record->conductor_externo_nombres,
                                    'celular'               => $this->record->conductor_externo_celular,
                                    'licencia_numero'       => $this->record->conductor_externo_licencia,
                                    'estado_disponibilidad' => 'OCUPADO',
                                    'activo'                => true,
                                ]
                            );
                            if (empty($data['id_conductor'])) {
                                $data['id_conductor'] = $externo->id_conductor;
                            }
                        }

                        // Crear asignación
                        $asignacion = AsignacionVehiculo::create([
                            'id_solicitud'    => $this->record->id_solicitud,
                            'id_proyecto'     => $this->record->id_proyecto,
                            'id_vehiculo'     => $data['id_vehiculo'] ?? null,
                            'id_conductor'    => $data['id_conductor'] ?? null,
                            'id_jefe_control' => Auth::id(),
                            'estado'          => 'ACTIVA',
                            'observaciones'   => $data['observaciones'] ?? null,
                        ]);

                        // Marcar disponibilidad
                        if ($asignacion->id_vehiculo) {
                            Vehiculo::where('id_vehiculo', $asignacion->id_vehiculo)->update(['estado' => 'OCUPADO']);
                        }
                        if ($asignacion->id_conductor) {
                            Conductor::where('id_conductor', $asignacion->id_conductor)->update(['estado_disponibilidad' => 'OCUPADO']);
                        }

                        // Cambiar estado
                        $this->record->update(['estado' => 'ASIGNADA']);
                    });

                    Notification::make()->title('Vehículo asignado.')->success()->send();
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                });
        }

        // Solicitar devolución (Jefe de Proyecto, Control, Admin) cuando está ASIGNADA
        if ($this->record->estado === 'ASIGNADA' && Auth::user()->hasAnyRole(['Jefe de Proyecto','Super Admin','Jefe de Control y Monitoreo'])) {
            $actions[] = Action::make('solicitarDevolucion')
                ->label('Solicitar devolución')
                ->icon('heroicon-m-arrow-uturn-left')
                ->action(function () {
                    $state = $this->form->getState();
                    $dev   = $state['devolucion'] ?? [];

                    $asignacion = $this->record->asignacion;
                    if (!$asignacion) {
                        Notification::make()->title('No existe asignación para esta solicitud.')->danger()->send();
                        return;
                    }

                    SolicitudDevolucion::create([
                        'id_asignacion'          => $asignacion->id_asignacion,
                        'id_usuario_solicitante' => Auth::id(),
                        'fotos_evidencia'        => $dev['fotos_evidencia'] ?? [],
                        'videos_evidencia'       => $dev['videos_evidencia'] ?? [],
                        'ubicacion_text'         => $dev['ubicacion_text'] ?? null,
                        'observaciones'          => $dev['observaciones'] ?? null,
                        'estado'                 => 'PENDIENTE',
                    ]);

                    $this->record->update(['estado' => 'EN DEVOLUCIÓN']);

                    Notification::make()->title('Solicitud de devolución enviada.')->success()->send();
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                });
        }

        // Validar devolución (Control/Asistente/Admin) cuando está EN DEVOLUCIÓN
        if ($this->record->estado === 'EN DEVOLUCIÓN' && Auth::user()->hasAnyRole(['Jefe de Control y Monitoreo','Asistente Control y Monitoreo','Super Admin'])) {
            $actions[] = Action::make('validarDevolucion')
                ->label('Validar devolución')
                ->color('success')
                ->icon('heroicon-m-check-badge')
                ->requiresConfirmation()
                ->action(function () {
                    DB::transaction(function () {
                        $asignacion = $this->record->asignacion;

                        if ($asignacion) {
                            $asignacion->update([
                                'estado' => 'FINALIZADA',
                                'fecha_finalizacion' => now(),
                            ]);

                            if ($asignacion->id_vehiculo) {
                                Vehiculo::where('id_vehiculo', $asignacion->id_vehiculo)->update(['estado' => 'DISPONIBLE']);
                            }
                            if ($asignacion->id_conductor) {
                                Conductor::where('id_conductor', $asignacion->id_conductor)->update(['estado_disponibilidad' => 'DISPONIBLE']);
                            }
                        }

                        $this->record->update(['estado' => 'FINALIZADA']);
                    });

                    Notification::make()->title('Devolución validada. Proceso finalizado.')->success()->send();
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                });
        }

        // Guardar normal
        $actions[] = $this->getSaveFormAction();

        return $actions;
    }
}
