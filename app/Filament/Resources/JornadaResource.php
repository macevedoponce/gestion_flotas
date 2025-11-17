<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JornadaResource\Pages;
use App\Models\Jornada;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Filters\Filter;

use App\Filament\Imports\JornadaImporter;
use App\Filament\Exports\JornadaExporter;

class JornadaResource extends Resource
{
    protected static ?string $model = Jornada::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Jornada';
    protected static ?string $pluralModelLabel = 'Jornadas';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_asignacion')
                ->relationship('asignacion', 'id_asignacion')
                ->label('Asignación')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('id_conductor')
                ->relationship('conductor', 'nombre_completo')
                ->label('Conductor')
                ->searchable()
                ->required(),

            Forms\Components\DatePicker::make('dia_operativo')
                ->required(),

            Forms\Components\DateTimePicker::make('fecha_inicio'),

            Forms\Components\DateTimePicker::make('fecha_fin'),

            Forms\Components\Select::make('estado')
                ->options([
                    'EN_CURSO' => 'En curso',
                    'FINALIZADA' => 'Finalizada',
                    'CANCELADA' => 'Cancelada',
                ])
                ->required(),

            Forms\Components\Textarea::make('observaciones')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_jornada')->sortable(),
                Tables\Columns\TextColumn::make('asignacion.id_asignacion')
                    ->label('Asignación')
                    ->sortable(),
                Tables\Columns\TextColumn::make('conductor.nombre_completo')
                    ->label('Conductor')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dia_operativo')->date(),
                Tables\Columns\TextColumn::make('estado')->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Filter::make('en_curso')->query(fn ($q) => $q->where('estado', 'EN_CURSO')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

                Tables\Actions\ImportAction::make()
                    ->label('Importar')
                    ->importer(JornadaImporter::class),

                Tables\Actions\ExportAction::make()
                    ->label('Exportar')
                    ->exporter(JornadaExporter::class),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
