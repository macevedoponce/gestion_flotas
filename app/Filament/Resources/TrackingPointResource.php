<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackingPointResource\Pages;
use App\Models\TrackingPoint;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class TrackingPointResource extends Resource
{
    protected static ?string $model = TrackingPoint::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'GPS y Devoluciones';
    protected static ?string $label = 'Punto GPS';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_jornada')
                ->relationship('jornada', 'id')
                ->label('Jornada')
                ->required(),

            Forms\Components\Select::make('id_conductor')
                ->relationship('conductor', 'nombre_completo')
                ->label('Conductor'),

            Forms\Components\DateTimePicker::make('timestamp_ubicacion')
                ->label('Fecha y hora')
                ->required(),

            Forms\Components\TextInput::make('geom')
                ->label('Coordenadas (lat,long)')
                ->helperText('Formato: POINT(lon lat)')
                ->required(),

            Forms\Components\TextInput::make('velocidad')->numeric()->label('Velocidad (km/h)'),
            Forms\Components\TextInput::make('heading')->numeric()->label('Rumbo (°)'),
            Forms\Components\TextInput::make('precision')->numeric(),
            Forms\Components\TextInput::make('bateria_porcentaje')->numeric(),
            Forms\Components\Select::make('origen')->options([
                'APP' => 'App móvil',
                'SERVER' => 'Servidor',
                'MANUAL' => 'Manual',
            ]),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('jornada.id')->label('Jornada'),
            Tables\Columns\TextColumn::make('conductor.nombre_completo')->label('Conductor'),
            Tables\Columns\TextColumn::make('timestamp_ubicacion')->dateTime(),
            Tables\Columns\TextColumn::make('velocidad')->sortable(),
            Tables\Columns\TextColumn::make('bateria_porcentaje')->label('Batería (%)'),
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
