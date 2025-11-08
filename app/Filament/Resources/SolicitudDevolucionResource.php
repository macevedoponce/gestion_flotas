<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitudDevolucionResource\Pages;
use App\Models\SolicitudDevolucion;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class SolicitudDevolucionResource extends Resource
{
    protected static ?string $model = SolicitudDevolucion::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'GPS y Devoluciones';
    protected static ?string $label = 'Solicitud de Devolución';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_asignacion')
                ->relationship('asignacion', 'id_asignacion')
                ->label('Asignación')
                ->required(),

            Forms\Components\Select::make('id_usuario_solicitante')
                ->relationship('solicitante', 'name')
                ->label('Solicitante'),

            Forms\Components\DateTimePicker::make('fecha_solicitud')->label('Fecha solicitud'),

            Forms\Components\FileUpload::make('fotos_evidencia')
                ->multiple()
                ->directory('devoluciones/fotos')
                ->label('Fotos de evidencia'),

            Forms\Components\FileUpload::make('videos_evidencia')
                ->multiple()
                ->directory('devoluciones/videos')
                ->label('Videos de evidencia'),

            Forms\Components\TextInput::make('ubicacion_text')->label('Ubicación'),
            Forms\Components\Textarea::make('observaciones')->rows(3),

            Forms\Components\Select::make('estado')->options([
                'PENDIENTE' => 'Pendiente',
                'VALIDADA' => 'Validada',
                'RECHAZADA' => 'Rechazada',
            ])->default('PENDIENTE'),

            Forms\Components\Select::make('validado_por')
                ->relationship('validador', 'name')
                ->label('Revisado por'),

            Forms\Components\DateTimePicker::make('fecha_revision'),
            Forms\Components\Textarea::make('comentarios_revision')->rows(3),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('asignacion.id_asignacion')->label('Asignación'),
            Tables\Columns\TextColumn::make('solicitante.name')->label('Solicitante'),
            Tables\Columns\TextColumn::make('estado')->badge(),
            Tables\Columns\TextColumn::make('fecha_solicitud')->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSolicitudDevolucions::route('/'),
            'create' => Pages\CreateSolicitudDevolucion::route('/create'),
            'edit' => Pages\EditSolicitudDevolucion::route('/{record}/edit'),
        ];
    }
}
