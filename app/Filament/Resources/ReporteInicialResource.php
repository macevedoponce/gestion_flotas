<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReporteInicialResource\Pages;
use App\Models\ReporteInicial;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class ReporteInicialResource extends Resource
{
    protected static ?string $model = ReporteInicial::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Jornadas y Reportes';
    protected static ?string $label = 'Reporte Inicial';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_jornada')
                ->relationship('jornada', 'id')
                ->label('Jornada')
                ->required()
                ->searchable(),

            Forms\Components\TextInput::make('km_inicial')->numeric()->required(),

            Forms\Components\FileUpload::make('foto_km_inicial')
                ->label('Foto del tablero (KM)')
                ->image()
                ->directory('reportes/fotos_km_inicial'),

            Forms\Components\Textarea::make('motivo_traslado')->rows(2),
            Forms\Components\TextInput::make('destino')->maxLength(255),

            Forms\Components\TextInput::make('cantidad_acompanantes')->numeric(),

            Forms\Components\KeyValue::make('acompanantes')
                ->label('Acompañantes (nombre - cargo)'),

            Forms\Components\TextInput::make('ubicacion_inicio')->maxLength(255),
            Forms\Components\Toggle::make('checklist_completado')->label('Checklist completado'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jornada.id')->label('Jornada'),
                Tables\Columns\TextColumn::make('km_inicial')->sortable(),
                Tables\Columns\BooleanColumn::make('checklist_completado'),
                Tables\Columns\TextColumn::make('destino')->searchable(),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReporteInicials::route('/'),
            'create' => Pages\CreateReporteInicial::route('/create'),
            'edit' => Pages\EditReporteInicial::route('/{record}/edit'),
        ];
    }
}
