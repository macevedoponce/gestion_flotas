<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChecklistRespuestaResource\Pages;
use App\Models\ChecklistRespuesta;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class ChecklistRespuestaResource extends Resource
{
    protected static ?string $model = ChecklistRespuesta::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Jornadas y Reportes';
    protected static ?string $label = 'Respuestas de Checklist';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_reporte_inicial')
                ->relationship('reporteInicial', 'id')
                ->label('Reporte Inicial')
                ->required()
                ->searchable(),

            Forms\Components\Select::make('id_item')
                ->relationship('item', 'nombre')
                ->label('Ítem del Checklist')
                ->required(),

            Forms\Components\TextInput::make('valor_texto')->label('Valor texto'),
            Forms\Components\TextInput::make('valor_numero')->numeric()->label('Valor numérico'),
            Forms\Components\Toggle::make('valor_booleano')->label('Valor booleano'),
            Forms\Components\DatePicker::make('valor_fecha')->label('Valor fecha'),
            Forms\Components\KeyValue::make('valor_json')->label('Valor JSON'),
            Forms\Components\FileUpload::make('valor_imagen')->image()->directory('checklist/imagenes'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reporteInicial.id')->label('Reporte Inicial'),
                Tables\Columns\TextColumn::make('item.nombre')->label('Ítem'),
                Tables\Columns\TextColumn::make('valor_texto')->limit(30),
                Tables\Columns\IconColumn::make('valor_booleano')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklistRespuestas::route('/'),
            'create' => Pages\CreateChecklistRespuesta::route('/create'),
            'edit' => Pages\EditChecklistRespuesta::route('/{record}/edit'),
        ];
    }
}
