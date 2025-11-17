<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbastecimientoResource\Pages;
use App\Models\Abastecimiento;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Tables;

class AbastecimientoResource extends Resource
{
    protected static ?string $model = Abastecimiento::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Abastecimiento';
    protected static ?string $pluralModelLabel = 'Abastecimientos';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([

            Forms\Components\Section::make('Datos del abastecimiento')
                ->schema([

                    Forms\Components\TextInput::make('km_reportado')
                        ->label('KM reportado')
                        ->disabled(),

                    Forms\Components\FileUpload::make('foto_tablero_antes')
                        ->label('Tablero antes del abastecimiento')
                        ->disabled(),

                    Forms\Components\FileUpload::make('foto_surtidor_cero')
                        ->label('Surtidor (antes)')
                        ->disabled(),

                    Forms\Components\FileUpload::make('foto_tablero_despues')
                        ->label('Tablero después del abastecimiento')
                        ->disabled(),

                    Forms\Components\FileUpload::make('foto_surtidor_final')
                        ->label('Surtidor (final)')
                        ->disabled(),

                    Forms\Components\FileUpload::make('foto_comprobante')
                        ->label('Comprobante')
                        ->disabled(),

                ])
                ->columns(2),

            Forms\Components\Section::make('Verificación')
                ->schema([

                    Forms\Components\Select::make('estado_verificacion')
                        ->label('Estado de verificación')
                        ->options([
                            'PENDIENTE' => 'Pendiente',
                            'APROBADO' => 'Aprobado',
                            'RECHAZADO' => 'Rechazado',
                            'SOSPECHOSO' => 'Sospechoso',
                        ])
                        ->required(),

                    Forms\Components\Textarea::make('observacion_verificacion')
                        ->label('Observaciones de verificación')
                        ->nullable()
                        ->columnSpanFull(),

                    Forms\Components\Select::make('verificado_por')
                        ->relationship('verificador', 'name')
                        ->label('Verificado por')
                        ->required(),

                    Forms\Components\DateTimePicker::make('fecha_verificacion')
                        ->label('Fecha de verificación')
                        ->default(now())
                        ->seconds(false),

                ])
                ->columns(2),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id_abastecimiento')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jornada.id_jornada')
                    ->label('Jornada')
                    ->sortable(),

                Tables\Columns\TextColumn::make('conductor.nombre_completo')
                    ->label('Conductor')
                    ->sortable(),

                Tables\Columns\TextColumn::make('km_reportado')
                    ->label('KM reportado'),

                Tables\Columns\TextColumn::make('estado_verificacion')
                ->label('Verificación')
                ->badge()
                ->colors([
                    'secondary' => 'PENDIENTE',
                    'success' => 'APROBADO',
                    'danger' => 'RECHAZADO',
                    'warning' => 'SOSPECHOSO',
                ]),

                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->dateTime(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado_verificacion')
                    ->label('Estado')
                    ->options([
                        'PENDIENTE' => 'Pendiente',
                        'APROBADO' => 'Aprobado',
                        'RECHAZADO' => 'Rechazado',
                        'SOSPECHOSO' => 'Sospechoso',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist->schema([

            Infolists\Components\Section::make('Datos del abastecimiento')
                ->schema([

                    Infolists\Components\TextEntry::make('fecha')->label('Fecha'),
                    Infolists\Components\TextEntry::make('km_reportado')->label('KM reportado'),

                    Infolists\Components\ImageEntry::make('foto_tablero_antes')
                        ->label('Tablero antes')
                        ->columnSpanFull(),

                    Infolists\Components\ImageEntry::make('foto_surtidor_cero')
                        ->label('Surtidor (antes)')
                        ->columnSpanFull(),

                    Infolists\Components\ImageEntry::make('foto_tablero_despues')
                        ->label('Tablero después')
                        ->columnSpanFull(),

                    Infolists\Components\ImageEntry::make('foto_surtidor_final')
                        ->label('Surtidor (final)')
                        ->columnSpanFull(),

                    Infolists\Components\ImageEntry::make('foto_comprobante')
                        ->label('Comprobante')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Infolists\Components\Section::make('Verificación')
                ->schema([
                    Infolists\Components\TextEntry::make('estado_verificacion'),
                    Infolists\Components\TextEntry::make('verificador.name')->label('Verificado por'),
                    Infolists\Components\TextEntry::make('fecha_verificacion')->dateTime(),

                    Infolists\Components\TextEntry::make('observacion_verificacion')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
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
