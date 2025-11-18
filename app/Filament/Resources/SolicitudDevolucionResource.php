<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitudDevolucionResource\Pages;
use App\Models\SolicitudDevolucion;
use App\Models\AsignacionVehiculo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;

class SolicitudDevolucionResource extends Resource
{
    protected static ?string $model = SolicitudDevolucion::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'Gestión Vehicular';
    protected static ?string $navigationLabel = 'Devoluciones de Vehículos';
    protected static ?string $modelLabel = 'Devolución';
    protected static ?string $pluralModelLabel = 'Devoluciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Datos generales')
                    ->schema([
                        Forms\Components\Placeholder::make('id')
                            ->label('ID devolución')
                            ->content(fn ($record) => $record?->id_devolucion ?? '-'),

                        Forms\Components\Placeholder::make('asignacion')
                            ->label('Asignación')
                            ->content(fn ($record) =>
                                $record?->asignacion?->id_asignacion
                                    ? ('#' . $record->asignacion->id_asignacion)
                                    : '-'
                            ),

                        Forms\Components\Placeholder::make('vehiculo')
                            ->label('Vehículo')
                            ->content(fn ($record) =>
                                $record?->asignacion?->vehiculo?->placa ?? '-'
                            ),

                        Forms\Components\Placeholder::make('proyecto')
                            ->label('Proyecto')
                            ->content(fn ($record) =>
                                $record?->asignacion?->proyecto?->descripcion ?? '-'
                            ),

                        Forms\Components\Placeholder::make('conductor')
                            ->label('Conductor')
                            ->content(fn ($record) =>
                                $record?->asignacion?->conductor?->nombre_completo ?? '-'
                            ),

                        Forms\Components\Placeholder::make('estado')
                            ->label('Estado actual')
                            ->content(fn ($record) =>
                                $record?->estado ?? '-'
                            ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Evidencias del conductor')
                    ->schema([
                        Forms\Components\Placeholder::make('evidencias_html')
                            ->content(function ($record) {
                                if (! $record) return '';

                                $html = "<div class='space-y-6'>";

                                $addImage = fn ($label, $path) =>
                                    "<div><h3 class='font-semibold mb-1'>$label</h3><img src='" . e(Storage::url($path)) . "' class='rounded-lg border max-w-md'/></div>";

                                if ($record->evidencia_foto_km_dev) {
                                    $html .= $addImage('Foto del tablero (KM)', $record->evidencia_foto_km_dev);
                                }
                                if ($record->evidencia_foto_frontal_dev) {
                                    $html .= $addImage('Frontal', $record->evidencia_foto_frontal_dev);
                                }
                                if ($record->evidencia_foto_posterior_dev) {
                                    $html .= $addImage('Posterior', $record->evidencia_foto_posterior_dev);
                                }
                                if ($record->evidencia_foto_lat_izq_dev) {
                                    $html .= $addImage('Lateral izquierda', $record->evidencia_foto_lat_izq_dev);
                                }
                                if ($record->evidencia_foto_lat_der_dev) {
                                    $html .= $addImage('Lateral derecha', $record->evidencia_foto_lat_der_dev);
                                }

                                if (is_array($record->evidencia_fotos_extra_dev)) {
                                    $html .= "<h3 class='font-semibold'>Fotos adicionales</h3>
                                              <div class='grid grid-cols-3 gap-4'>";
                                    foreach ($record->evidencia_fotos_extra_dev as $foto) {
                                        $html .= "<img src='" . e(Storage::url($foto)) . "' class='rounded border' />";
                                    }
                                    $html .= "</div>";
                                }

                                if ($record->evidencia_observaciones_dev) {
                                    $html .= "<div><h3 class='font-semibold'>Observaciones del conductor</h3>";
                                    $html .= "<p class='p-3 rounded bg-gray-50'>" . e($record->evidencia_observaciones_dev) . "</p></div>";
                                }

                                if ($record->evidencia_ubicacion_text_dev) {
                                    $html .= "<div><h3 class='font-semibold'>Ubicación textual</h3>";
                                    $html .= "<p class='p-3 rounded bg-gray-50'>" . e($record->evidencia_ubicacion_text_dev) . "</p></div>";
                                }

                                return new HtmlString($html . "</div>");
                            }),
                    ])
                    ->columns(1)
                    ->collapsed(),
            ]);
    }

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

                Tables\Columns\TextColumn::make('asignacion.vehiculo.placa')
                    ->label('Vehículo')
                    ->sortable(),

                Tables\Columns\TextColumn::make('asignacion.proyecto.descripcion')
                    ->label('Proyecto')
                    ->limit(40),

                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'warning' => 'PENDIENTE_EVIDENCIAS_CONDUCTOR',
                        'info'    => 'PENDIENTE_REVISION_JEFE_PROYECTO',
                        'primary' => 'PENDIENTE_REVISION_CONTROL',
                        'success' => 'APROBADA',
                        'danger'  => fn($state) => 
                            in_array($state, ['RECHAZADO_POR_CONTROL', 'RECHAZADO_POR_PROYECTO']),
                    ])
                    ->label('Estado'),

                Tables\Columns\TextColumn::make('fecha_solicitud')
                    ->dateTime('d/m/Y H:i')
                    ->label('Fecha solicitud'),
            ])
            ->defaultSort('id_devolucion', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('bitacora')
                    ->label('Bitácora')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->modalHeading('Bitácora de devolución')
                    ->modalWidth('4xl')
                    ->modalContent(function ($record) {

                        $logs = $record->asignacion->logs()
                            ->where('accion', 'like', '%Devolución%')
                            ->orWhere('accion', 'like', '%evidencia%')
                            ->get();

                        if ($logs->isEmpty()) {
                            return new HtmlString("<p class='p-4 text-gray-500'>Sin registros para esta devolución.</p>");
                        }

                        $html = "<div class='space-y-6'>";
                        foreach ($logs as $log) {

                            $html .= "
                                <div class='border-l-2 border-gray-300 pl-4'>
                                    <div class='p-3 bg-white rounded-lg shadow'>
                                        <div class='flex justify-between'>
                                            <strong>{$log->accion}</strong>
                                            <span class='text-xs text-gray-500'>
                                                {$log->created_at->format('d/m/Y H:i')}
                                            </span>
                                        </div>
                                        <pre class='text-sm mt-2 bg-gray-50 p-2 rounded'>{$log->detalles_texto}</pre>
                                    </div>
                                </div>";
                        }
                        $html .= "</div>";

                        return new HtmlString($html);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSolicitudDevolucions::route('/'),
        ];
    }
}
