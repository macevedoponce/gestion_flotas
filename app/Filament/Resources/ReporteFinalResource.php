<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReporteFinalResource\Pages;
use App\Models\ReporteFinal;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class ReporteFinalResource extends Resource
{
    protected static ?string $model = ReporteFinal::class;
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationGroup = 'Jornadas y Reportes';
    protected static ?string $label = 'Reporte Final';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_jornada')
                ->relationship('jornada', 'id')
                ->label('Jornada')
                ->required(),

            Forms\Components\TextInput::make('km_final')->numeric()->required(),
            Forms\Components\FileUpload::make('foto_km_final')
                ->label('Foto tablero final')
                ->image()
                ->directory('reportes/final'),

            Forms\Components\FileUpload::make('fotos_adicionales')
                ->multiple()
                ->image()
                ->directory('reportes/final/adicionales'),

            Forms\Components\TextInput::make('ubicacion_fin')->maxLength(255),
            Forms\Components\Textarea::make('observaciones')->rows(3),

            Forms\Components\Toggle::make('es_jornada_extendida')->label('Jornada extendida'),
            Forms\Components\TextInput::make('horas_totales')->numeric(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jornada.id')->label('Jornada'),
                Tables\Columns\TextColumn::make('km_final')->sortable(),
                Tables\Columns\IconColumn::make('es_jornada_extendida')->boolean(),
                Tables\Columns\TextColumn::make('horas_totales')->label('Horas totales'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReporteFinals::route('/'),
            'create' => Pages\CreateReporteFinal::route('/create'),
            'edit' => Pages\EditReporteFinal::route('/{record}/edit'),
        ];
    }
}
