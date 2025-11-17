<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReporteInicialResource\Pages;
use App\Models\ReporteInicial;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions;

class ReporteInicialResource extends Resource
{
    protected static ?string $model = ReporteInicial::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Reporte Inicial';
    protected static ?string $pluralModelLabel = 'Reportes Iniciales';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([

            Forms\Components\Section::make('Datos enviados por el conductor')
                ->schema([
                    Forms\Components\TextInput::make('km_inicial')
                        ->label('KM inicial (reportado)')
                        ->disabled(),

                    Forms\Components\FileUpload::make('foto_km_inicial')
                        ->label('Foto del KM inicial')
                        ->disabled(),

                    Forms\Components\Textarea::make('motivo_traslado')
                        ->disabled(),

                    Forms\Components\TextInput::make('destino')
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
                        ->label('Observación de validación')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('validado_por')
                        ->relationship('validador', 'name')
                        ->label('Validado por')
                        ->required(),

                    Forms\Components\DateTimePicker::make('validado_en')
                        ->label('Validado en')
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

                Tables\Columns\TextColumn::make('id_reporte_inicial')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jornada.id_jornada')
                    ->label('Jornada')
                    ->sortable(),

                Tables\Columns\TextColumn::make('km_inicial')
                    ->label('KM reportado'),

                Tables\Columns\IconColumn::make('checklist_completado')
                    ->label('Checklist')
                    ->boolean(),

                Tables\Columns\TextColumn::make('estado_validacion')
                    ->label('Validación')
                    ->badge()
                    ->colors([
                        'secondary' => 'PENDIENTE',
                        'success' => 'VALIDO',
                        'warning' => 'CORREGIDO',
                        'danger' => 'RECHAZADO',
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

            Infolists\Components\Section::make('Datos del reporte')
                ->schema([
                    Infolists\Components\TextEntry::make('km_inicial')->label('KM inicial reportado'),
                    Infolists\Components\TextEntry::make('destino')->label('Destino'),
                    Infolists\Components\TextEntry::make('motivo_traslado')->label('Motivo'),

                    Infolists\Components\ImageEntry::make('foto_km_inicial')
                        ->label('Foto KM inicial')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Infolists\Components\Section::make('Validación')
                ->schema([
                    Infolists\Components\TextEntry::make('km_validado')->label('KM validado'),
                    Infolists\Components\TextEntry::make('estado_validacion')->label('Estado validación'),
                    Infolists\Components\TextEntry::make('validador.name')->label('Validado por'),
                    Infolists\Components\TextEntry::make('validado_en')->label('Validado en')->dateTime(),
                    Infolists\Components\TextEntry::make('observacion_validacion')
                        ->label('Observación')
                        ->columnSpanFull(),
                ])
                ->columns(2),

        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReporteInicials::route('/'),
            'create' => Pages\CreateReporteInicial::route('/create'),
            'edit' => Pages\EditReporteInicial::route('/{record}/edit'),
        ];
    }
}
