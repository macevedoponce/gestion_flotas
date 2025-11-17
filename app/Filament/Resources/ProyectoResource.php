<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Models\Proyecto;
use App\Models\User;
use App\Models\Ceco;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Imports\ProyectoImporter;
use App\Filament\Exports\ProyectoExporter;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Catálogos';
    protected static ?int $navigationSort = 5;

    protected static ?string $label = 'Proyecto';
    protected static ?string $pluralLabel = 'Proyectos';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Información del Proyecto')
                ->schema([
                    Forms\Components\TextInput::make('codigo_anexo')
                        ->label('Código Anexo')
                        ->required()
                        ->length(14)
                        ->unique(ignoreRecord: true),

                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(3)
                        ->required(),

                    Forms\Components\Select::make('responsable_id')
                        ->label('Responsable')
                        ->relationship('responsable', 'name')
                        ->searchable()
                        ->nullable(),

                    Forms\Components\Select::make('id_ceco')
                        ->label('CECO')
                        ->relationship('ceco', 'codigo')
                        ->searchable()
                        ->nullable(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Detalles operativos')
                ->schema([
                    Forms\Components\TextInput::make('lugar_trabajo')
                        ->label('Lugar de Trabajo')
                        ->nullable()
                        ->maxLength(200),

                    Forms\Components\DatePicker::make('fecha_inicio')
                        ->label('Fecha Inicio')
                        ->nullable(),

                    Forms\Components\DatePicker::make('fecha_fin')
                        ->label('Fecha Fin')
                        ->nullable(),

                    Forms\Components\Select::make('estado')
                        ->label('Estado')
                        ->options([
                            'ACTIVO'   => 'ACTIVO',
                            'INACTIVO' => 'INACTIVO',
                            'CERRADO'  => 'CERRADO',
                        ])
                        ->default('ACTIVO')
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('codigo_anexo')
                    ->label('Código')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->sortable()
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('ceco.codigo')
                    ->label('CECO')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('responsable.name')
                    ->label('Responsable')
                    ->searchable(),

                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'ACTIVO' => 'ACTIVO',
                        'INACTIVO' => 'INACTIVO',
                        'CERRADO' => 'CERRADO',
                    ]),
            ])

            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\ImportAction::make()->importer(ProyectoImporter::class),
                Tables\Actions\ExportAction::make()->exporter(ProyectoExporter::class),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'edit'   => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
