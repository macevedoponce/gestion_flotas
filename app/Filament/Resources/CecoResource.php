<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CecoResource\Pages;
use App\Models\Ceco;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class CecoResource extends Resource
{
    protected static ?string $model = Ceco::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Gestión Financiera';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('codigo_ceco')
                ->label('Código CECO')
                ->required()
                ->maxLength(14)
                ->unique(Ceco::class, 'codigo_ceco', ignoreRecord: true),

            Forms\Components\TextInput::make('descripcion_ceco')
                ->label('Descripción')
                ->required()
                ->maxLength(200),

            Forms\Components\Select::make('responsable_id')
                ->label('Responsable')
                ->relationship('responsable', 'name')
                ->searchable()
                ->preload(),

            Forms\Components\Select::make('tipo_ceco')
                ->label('Tipo CECO')
                ->options([
                    'OPEX' => 'OPEX',
                    'CAPEX' => 'CAPEX',
                ])
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_ceco')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('descripcion_ceco')
                    ->label('Descripción')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('responsable.name')
                    ->label('Responsable'),

                Tables\Columns\BadgeColumn::make('tipo_ceco')
                    ->label('Tipo')
                    ->color(fn (string $state): string => match ($state) {
                        'OPEX' => 'warning',
                        'CAPEX' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('exportar')
                    ->label('Exportar')
                    ->fileName('cecos_' . now()->format('Ymd_His'))
                    ->defaultFormat('xlsx'),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('exportar')
                    ->label('Exportar seleccionados')
                    ->fileName('cecos_' . now()->format('Ymd_His'))
                    ->defaultFormat('xlsx'),
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCecos::route('/'),
            'create' => Pages\CreateCeco::route('/create'),
            'edit' => Pages\EditCeco::route('/{record}/edit'),
        ];
    }
}
