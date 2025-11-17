<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoCombustibleResource\Pages;
use App\Models\TipoCombustible;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Imports\TipoCombustibleImporter;
use App\Filament\Exports\TipoCombustibleExporter;

class TipoCombustibleResource extends Resource
{
    protected static ?string $model = TipoCombustible::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationGroup = 'Catálogos';
    protected static ?string $label = 'Tipo de Combustible';
    protected static ?string $pluralLabel = 'Tipos de Combustible';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Información')
                ->schema([
                    Forms\Components\TextInput::make('nombre')
                        ->label('Nombre')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100)
                        ->autocomplete(false),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_tipo_combustible')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Creado')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Actualizado')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('nombre')
                    ->label('Filtrar por nombre')
                    ->form([
                        Forms\Components\TextInput::make('nombre'),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when($data['nombre'], fn ($q) =>
                            $q->where('nombre', 'ILIKE', "%{$data['nombre']}%")
                        )
                    ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\ImportAction::make()
                    ->label('Importar')
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->importer(TipoCombustibleImporter::class),

                Tables\Actions\ExportAction::make()
                    ->label('Exportar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(TipoCombustibleExporter::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTipoCombustibles::route('/'),
            'create' => Pages\CreateTipoCombustible::route('/create'),
            'edit' => Pages\EditTipoCombustible::route('/{record}/edit'),
        ];
    }
}
