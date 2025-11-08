<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConductorResource\Pages;
use App\Models\Conductor;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;

class ConductorResource extends Resource
{
    protected static ?string $model = Conductor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Personal';
    protected static ?string $modelLabel = 'Conductor';
    protected static ?string $pluralModelLabel = 'Conductores';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Personal')
                    ->schema([
                        Forms\Components\TextInput::make('nombre_completo')
                            ->required()
                            ->maxLength(150)
                            ->label('Nombre Completo'),
                            
                        Forms\Components\TextInput::make('documento_identidad')
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->label('DNI')
                            ->required(),
                            
                        Forms\Components\TextInput::make('celular')
                            ->maxLength(30)
                            ->tel()
                            ->label('Celular'),
                    ])
                    ->columns(3),
                    
                Forms\Components\Section::make('Información de Licencia')
                    ->schema([
                        Forms\Components\TextInput::make('licencia_numero')
                            ->maxLength(80)
                            ->label('Número de Licencia'),
                            
                        Forms\Components\Select::make('licencia_categoria')
                            ->options([
                                'A-I' => 'A-I (Motocicletas)',
                                'A-IIa' => 'A-IIa (Autos hasta 1500 kg)',
                                'A-IIb' => 'A-IIb (Autos hasta 3500 kg)',
                                'A-IIIa' => 'A-IIIa (Camiones hasta 10000 kg)',
                                'A-IIIb' => 'A-IIIb (Camiones hasta 15000 kg)',
                                'A-IIIc' => 'A-IIIc (Camiones articulados)',
                                'B-I' => 'B-I (Maquinaria liviana)',
                                'B-II' => 'B-II (Maquinaria pesada)',
                            ])
                            ->searchable()
                            ->label('Categoría'),
                            
                        Forms\Components\DatePicker::make('licencia_vencimiento')
                            ->label('Vencimiento de Licencia'),
                    ])
                    ->columns(3),
                    
                Forms\Components\Section::make('Credenciales de App')
                    ->schema([
                        Forms\Components\TextInput::make('username_app')
                            ->label('Usuario App')
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->helperText('Sugerencia: usar el mismo DNI'),
                            
                        Forms\Components\TextInput::make('password_hash')
                            ->label('Contraseña Temporal')
                            ->password()
                            ->required()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText('Sugerencia: usar el mismo DNI'),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Estado')
                    ->schema([
                        Forms\Components\Select::make('estado_disponibilidad')
                            ->options([
                                'DISPONIBLE' => 'Disponible',
                                'NO_DISPONIBLE' => 'No Disponible',
                                'VACACIONES' => 'Vacaciones',
                                'LICENCIA' => 'Licencia',
                                'CAPACITACION' => 'En Capacitación',
                            ])
                            ->default('DISPONIBLE')
                            ->label('Estado de Disponibilidad'),
                            
                        Forms\Components\Toggle::make('activo')
                            ->default(true)
                            ->label('Activo en el sistema'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre_completo')
                    ->searchable()
                    ->sortable()
                    ->label('Nombre'),
                    
                Tables\Columns\TextColumn::make('documento_identidad')
                    ->searchable()
                    ->label('DNI'),
                    
                Tables\Columns\TextColumn::make('celular')
                    ->searchable()
                    ->label('Celular'),
                    
                Tables\Columns\TextColumn::make('licencia_numero')
                    ->searchable()
                    ->label('Licencia'),
                    
                Tables\Columns\TextColumn::make('licencia_categoria')
                    ->label('Categoría'),
                    
                Tables\Columns\TextColumn::make('licencia_vencimiento')
                    ->date()
                    ->sortable()
                    ->label('Venc. Licencia')
                    ->color(fn ($record) => $record->licencia_vencimiento?->isPast() ? 'danger' : 'success'),
                    
                Tables\Columns\TextColumn::make('username_app')
                    ->searchable()
                    ->label('Usuario App'),
                    
                Tables\Columns\BadgeColumn::make('estado_disponibilidad')
                    ->colors([
                        'success' => 'DISPONIBLE',
                        'warning' => 'VACACIONES',
                        'gray' => 'LICENCIA',
                        'danger' => 'NO_DISPONIBLE',
                        'info' => 'CAPACITACION',
                    ])
                    ->label('Disponibilidad'),
                    
                Tables\Columns\ToggleColumn::make('activo')
                    ->label('Activo')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado_disponibilidad')
                    ->options([
                        'DISPONIBLE' => 'Disponible',
                        'NO_DISPONIBLE' => 'No Disponible',
                        'VACACIONES' => 'Vacaciones',
                        'LICENCIA' => 'Licencia',
                        'CAPACITACION' => 'En Capacitación',
                    ])
                    ->label('Estado de Disponibilidad'),
                    
                Tables\Filters\Filter::make('licencia_vencida')
                    ->query(fn ($query) => $query->where('licencia_vencimiento', '<', now()))
                    ->label('Licencia Vencida'),
                    
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Estado Activo')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos')
                    ->queries(
                        true: fn ($query) => $query->where('activo', true),
                        false: fn ($query) => $query->where('activo', false),
                        blank: fn ($query) => $query,
                    )
                    ->default(true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activar')
                        ->label('Activar seleccionados')
                        ->action(fn ($records) => $records->each->update(['activo' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('desactivar')
                        ->label('Desactivar seleccionados')
                        ->action(fn ($records) => $records->each->update(['activo' => false]))
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('nombre_completo');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConductors::route('/'),
            'create' => Pages\CreateConductor::route('/create'),
            'edit' => Pages\EditConductor::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('activo', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}