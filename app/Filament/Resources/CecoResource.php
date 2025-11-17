<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CecoResource\Pages;
use App\Models\Ceco;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

use App\Filament\Imports\CecoImporter;
use App\Filament\Exports\CecoExporter;

class CecoResource extends Resource
{
    protected static ?string $model = Ceco::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Catálogos';
    protected static ?int $navigationSort = 4;

    protected static ?string $label = 'CECO';
    protected static ?string $pluralLabel = 'CECOs';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Datos del CECO')
                ->schema([

                    Forms\Components\TextInput::make('codigo')
                        ->label('Código')
                        ->required()
                        ->maxLength(20)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('descripcion')
                        ->label('Descripción')
                        ->required()
                        ->maxLength(150),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])

            ->headerActions([
                Tables\Actions\CreateAction::make(),

                Tables\Actions\ImportAction::make()
                    ->label('Importar')
                    ->importer(CecoImporter::class),

                Tables\Actions\ExportAction::make()
                    ->label('Exportar')
                    ->exporter(CecoExporter::class),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCecos::route('/'),
            'create' => Pages\CreateCeco::route('/create'),
            'edit'   => Pages\EditCeco::route('/{record}/edit'),
        ];
    }
}
