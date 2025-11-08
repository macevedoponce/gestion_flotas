<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JornadaResource\Pages;
use App\Models\Jornada;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class JornadaResource extends Resource
{
    protected static ?string $model = Jornada::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Jornadas y Reportes';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_asignacion')
                ->relationship('asignacion', 'id_asignacion')
                ->label('Asignación')
                ->required()
                ->searchable(),

            Forms\Components\Select::make('id_conductor')
                ->relationship('conductor', 'nombre_completo')
                ->label('Conductor')
                ->required(),

            Forms\Components\DatePicker::make('dia_operativo')->required(),
            Forms\Components\DateTimePicker::make('fecha_inicio'),
            Forms\Components\DateTimePicker::make('fecha_fin'),

            Forms\Components\Select::make('estado')
                ->options([
                    'EN_CURSO' => 'En curso',
                    'FINALIZADA' => 'Finalizada',
                    'CANCELADA' => 'Cancelada',
                ])->default('EN_CURSO'),

            Forms\Components\Textarea::make('observaciones')->rows(3),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('asignacion.id_asignacion')->label('Asignación'),
            Tables\Columns\TextColumn::make('conductor.nombre_completo')->label('Conductor'),
            Tables\Columns\TextColumn::make('dia_operativo')->date(),
            Tables\Columns\TextColumn::make('estado')->badge(),
        ])
        ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJornadas::route('/'),
            'create' => Pages\CreateJornada::route('/create'),
            'edit' => Pages\EditJornada::route('/{record}/edit'),
        ];
    }
}
