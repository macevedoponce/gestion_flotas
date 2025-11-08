<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Proyectos y Solicitudes';
    protected static ?string $pluralLabel = 'Proyectos';
    protected static ?string $label = 'Proyecto';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('codigo_anexo')
                ->label('Código Anexo')
                ->unique(ignoreRecord: true)
                ->maxLength(14),
            Forms\Components\Textarea::make('descripcion')
                ->label('Descripción')
                ->required()
                ->rows(3),
            Forms\Components\Select::make('responsable_id')
                ->label('Responsable')
                ->relationship('responsable', 'name')
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make('lugar_trabajo')
                ->label('Lugar de Trabajo')
                ->maxLength(200),
            Forms\Components\DatePicker::make('fecha_inicio')->label('Inicio'),
            Forms\Components\DatePicker::make('fecha_fin')->label('Fin'),
            Forms\Components\Select::make('estado')
                ->options([
                    'ACTIVO' => 'Activo',
                    'FINALIZADO' => 'Finalizado',
                    'SUSPENDIDO' => 'Suspendido',
                ])
                ->default('ACTIVO'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_anexo')->label('Código')->sortable(),
                Tables\Columns\TextColumn::make('descripcion')->label('Descripción')->limit(50)->searchable(),
                Tables\Columns\TextColumn::make('responsable.name')->label('Responsable'),
                Tables\Columns\TextColumn::make('estado')->badge(),
                Tables\Columns\TextColumn::make('fecha_inicio')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('fecha_fin')->date('d/m/Y'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'edit' => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
