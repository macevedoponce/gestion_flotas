<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventoEmergenciaResource\Pages;
use App\Models\EventoEmergencia;
use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use Filament\Resources\Resource;

class EventoEmergenciaResource extends Resource
{
    protected static ?string $model = EventoEmergencia::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Evento de Emergencia';
    protected static ?string $pluralModelLabel = 'Eventos de Emergencia';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([

            Forms\Components\Select::make('id_tipo_evento')
                ->relationship('tipoEvento', 'nombre')
                ->label('Tipo de evento')
                ->required(),

            Forms\Components\Select::make('id_jornada')
                ->relationship('jornada', 'id_jornada')
                ->label('Jornada'),

            Forms\Components\Select::make('id_conductor')
                ->relationship('conductor', 'nombre_completo')
                ->label('Conductor'),

            Forms\Components\Select::make('id_vehiculo')
                ->relationship('vehiculo', 'placa')
                ->label('Vehículo'),

            Forms\Components\Textarea::make('descripcion')
                ->label('Descripción')
                ->rows(4),

            Forms\Components\FileUpload::make('fotos')
                ->label('Fotos')
                ->multiple(),

            Forms\Components\Select::make('estado')
                ->label('Estado')
                ->options([
                    'PENDIENTE' => 'Pendiente',
                    'EN_PROCESO' => 'En proceso',
                    'RESUELTO' => 'Resuelto',
                ])
                ->required(),

            Forms\Components\Select::make('atendido_por')
                ->relationship('atendidoPor', 'name')
                ->label('Atendido por')
                ->nullable(),

            Forms\Components\Textarea::make('comentario_cierre')
                ->label('Comentario de cierre')
                ->rows(3),

            Forms\Components\DateTimePicker::make('hora_cierre')
                ->label('Hora de cierre'),
        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([

            Tables\Columns\TextColumn::make('id_evento')->label('ID'),

            Tables\Columns\TextColumn::make('tipoEvento.nombre')
                ->label('Tipo')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('conductor.nombre_completo')
                ->label('Conductor'),

            Tables\Columns\TextColumn::make('vehiculo.placa')
                ->label('Vehículo'),

            Tables\Columns\TextColumn::make('estado')
                ->badge()
                ->colors([
                    'secondary' => 'PENDIENTE',
                    'warning'   => 'EN_PROCESO',
                    'success'   => 'RESUELTO',
                ]),

            Tables\Columns\TextColumn::make('hora_reporte')
                ->label('Reporte')
                ->dateTime(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('estado')
                ->options([
                    'PENDIENTE' => 'Pendiente',
                    'EN_PROCESO' => 'En proceso',
                    'RESUELTO' => 'Resuelto',
                ]),

            Tables\Filters\SelectFilter::make('id_tipo_evento')
                ->relationship('tipoEvento', 'nombre')
                ->label('Tipo'),

            Tables\Filters\SelectFilter::make('id_conductor')
                ->relationship('conductor', 'nombre_completo')
                ->label('Conductor'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([]);
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist->schema([

            Infolists\Components\Section::make('Detalles del evento')
                ->schema([
                    Infolists\Components\TextEntry::make('tipoEvento.nombre')->label('Tipo'),
                    Infolists\Components\TextEntry::make('descripcion'),
                    Infolists\Components\ImageEntry::make('fotos')->multiple()->columnSpanFull(),
                    Infolists\Components\TextEntry::make('estado')->badge(),
                    Infolists\Components\TextEntry::make('hora_reporte')->dateTime(),
                ])
                ->columns(2),

            Infolists\Components\Section::make('Cierre')
                ->schema([
                    Infolists\Components\TextEntry::make('atendidoPor.name')->label('Atendido por'),
                    Infolists\Components\TextEntry::make('comentario_cierre'),
                    Infolists\Components\TextEntry::make('hora_cierre')->dateTime(),
                ])
                ->columns(2),

        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventoEmergencias::route('/'),
            'create' => Pages\CreateEventoEmergencia::route('/create'),
            'edit' => Pages\EditEventoEmergencia::route('/{record}/edit'),
        ];
    }
}
