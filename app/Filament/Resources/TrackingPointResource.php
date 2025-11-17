<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackingPointResource\Pages;
use App\Models\TrackingPoint;
use Filament\Tables;
use Filament\Infolists;
use Filament\Resources\Resource;

class TrackingPointResource extends Resource
{
    protected static ?string $model = TrackingPoint::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Punto GPS';
    protected static ?string $pluralModelLabel = 'Tracking GPS';

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_tracking')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jornada.id_jornada')
                    ->label('Jornada')
                    ->sortable(),

                Tables\Columns\TextColumn::make('conductor.nombre_completo')
                    ->label('Conductor'),

                Tables\Columns\TextColumn::make('timestamp_ubicacion')
                    ->label('Hora')
                    ->dateTime(),

                Tables\Columns\TextColumn::make('velocidad')
                    ->label('Velocidad (km/h)'),

                Tables\Columns\TextColumn::make('heading')
                    ->label('Rumbo'),

                Tables\Columns\TextColumn::make('precision')
                    ->label('Precisión'),

                Tables\Columns\TextColumn::make('bateria_porcentaje')
                    ->label('Batería (%)'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_conductor')
                    ->label('Conductor')
                    ->relationship('conductor', 'nombre_completo'),

                Tables\Filters\SelectFilter::make('id_jornada')
                    ->label('Jornada')
                    ->relationship('jornada', 'id_jornada'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist->schema([

            Infolists\Components\Section::make('Datos del punto GPS')
                ->schema([
                    Infolists\Components\TextEntry::make('timestamp_ubicacion')->label('Fecha/Hora'),
                    Infolists\Components\TextEntry::make('velocidad')->label('Velocidad'),
                    Infolists\Components\TextEntry::make('heading')->label('Rumbo'),
                    Infolists\Components\TextEntry::make('precision')->label('Precisión'),
                    Infolists\Components\TextEntry::make('bateria_porcentaje')->label('Batería (%)'),
                    Infolists\Components\TextEntry::make('origen')->label('Origen'),
                ])
                ->columns(2),

        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrackingPoints::route('/'),
            'create' => Pages\CreateTrackingPoint::route('/create'),
            'edit' => Pages\EditTrackingPoint::route('/{record}/edit'),
        ];
    }
}
