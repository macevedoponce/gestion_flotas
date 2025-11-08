<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbastecimientoResource\Pages;
use App\Models\Abastecimiento;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class AbastecimientoResource extends Resource
{
    protected static ?string $model = Abastecimiento::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Jornadas y Reportes';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_jornada')
                ->relationship('jornada', 'id')
                ->required()
                ->label('Jornada'),

            Forms\Components\Select::make('id_conductor')
                ->relationship('conductor', 'nombre_completo')
                ->label('Conductor'),

            Forms\Components\DateTimePicker::make('fecha')->required(),

            Forms\Components\TextInput::make('km_reportado')->numeric()->required(),

            Forms\Components\FileUpload::make('foto_tablero_antes')->label('Foto tablero antes')->image()->directory('abastecimientos'),
            Forms\Components\FileUpload::make('foto_surtidor_cero')->label('Foto surtidor en cero')->image()->directory('abastecimientos'),
            Forms\Components\FileUpload::make('foto_tablero_despues')->label('Foto tablero después')->image()->directory('abastecimientos'),
            Forms\Components\FileUpload::make('foto_surtidor_final')->label('Foto surtidor final')->image()->directory('abastecimientos'),
            Forms\Components\FileUpload::make('foto_comprobante')->label('Comprobante')->image()->directory('abastecimientos'),

            Forms\Components\Select::make('estado_verificacion')
                ->options([
                    'PENDIENTE' => 'Pendiente',
                    'VALIDADO' => 'Validado',
                    'RECHAZADO' => 'Rechazado',
                ])
                ->default('PENDIENTE'),

            Forms\Components\TextInput::make('codigo_comprobante')->maxLength(100),
            Forms\Components\Textarea::make('observaciones')->rows(3),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jornada.id')->label('Jornada'),
                Tables\Columns\TextColumn::make('conductor.nombre_completo')->label('Conductor'),
                Tables\Columns\TextColumn::make('fecha')->dateTime(),
                Tables\Columns\TextColumn::make('estado_verificacion')->badge(),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbastecimientos::route('/'),
            'create' => Pages\CreateAbastecimiento::route('/create'),
            'edit' => Pages\EditAbastecimiento::route('/{record}/edit'),
        ];
    }
}
