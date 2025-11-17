<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReporteFinalResource\Pages;
use App\Models\ReporteFinal;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Tables;

class ReporteFinalResource extends Resource
{
    protected static ?string $model = ReporteFinal::class;
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Reporte Final';
    protected static ?string $pluralModelLabel = 'Reportes Finales';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([

            Forms\Components\Section::make('Datos enviados por el conductor')
                ->schema([
                    Forms\Components\TextInput::make('km_final')
                        ->label('KM final (reportado)')
                        ->disabled(),

                    Forms\Components\FileUpload::make('foto_km_final')
                        ->label('Foto KM final')
                        ->disabled(),

                    Forms\Components\FileUpload::make('fotos_adicionales')
                        ->label('Fotos adicionales')
                        ->multiple()
                        ->disabled(),

                    Forms\Components\Textarea::make('observaciones')
                        ->disabled(),

                ])->columns(2),

            Forms\Components\Section::make('Validación')
                ->schema([
                    Forms\Components\TextInput::make('km_validado')
                        ->label('KM validado (corregido)')
                        ->numeric()
                        ->nullable(),

                    Forms\Components\Select::make('estado_validacion')
                        ->label('Estado de validación')
                        ->options([
                            'PENDIENTE' => 'Pendiente',
                            'VALIDO' => 'Válido',
                            'CORREGIDO' => 'Corregido',
                            'RECHAZADO' => 'Rechazado',
                        ])
                        ->required(),

                    Forms\Components\Textarea::make('observacion_validacion')
                        ->label('Observación del validador')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('validado_por')
                        ->relationship('validador', 'name')
                        ->label('Validado por')
                        ->required(),

                    Forms\Components\DateTimePicker::make('validado_en')
                        ->label('Validado en')
                        ->default(now())
                        ->seconds(false),

                ])->columns(2),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id_reporte_final')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jornada.id_jornada')
                    ->label('Jornada')
                    ->sortable(),

                Tables\Columns\TextColumn::make('km_final')
                    ->label('KM final'),

                Tables\Columns\TextColumn::make('estado_validacion')
                    ->label('Validación')
                    ->badge()
                    ->colors([
                        'secondary' => 'PENDIENTE',
                        'success'   => 'VALIDO',
                        'warning'   => 'CORREGIDO',
                        'danger'    => 'RECHAZADO',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado_validacion')
                    ->label('Estado de validación')
                    ->options([
                        'PENDIENTE' => 'Pendiente',
                        'VALIDO' => 'Válido',
                        'CORREGIDO' => 'Corregido',
                        'RECHAZADO' => 'Rechazado',
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
            Infolists\Components\Section::make('Datos del reporte final')
                ->schema([
                    Infolists\Components\TextEntry::make('km_final')->label('KM reportado'),
                    Infolists\Components\TextEntry::make('observaciones'),
                    Infolists\Components\ImageEntry::make('foto_km_final')
                        ->label('Foto KM final')
                        ->columnSpanFull(),

                    Infolists\Components\ImageEntry::make('fotos_adicionales')
                        ->label('Fotos adicionales')
                        ->multiple()
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Infolists\Components\Section::make('Validación')
                ->schema([
                    Infolists\Components\TextEntry::make('km_validado'),
                    Infolists\Components\TextEntry::make('estado_validacion'),
                    Infolists\Components\TextEntry::make('validador.name')->label('Validado por'),
                    Infolists\Components\TextEntry::make('validado_en')->dateTime()->label('Validado en'),
                    Infolists\Components\TextEntry::make('observacion_validacion')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReporteFinals::route('/'),
            'create' => Pages\CreateReporteFinal::route('/create'),
            'edit' => Pages\EditReporteFinal::route('/{record}/edit'),
        ];
    }
}
