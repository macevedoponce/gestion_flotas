<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConductorResource\Pages;
use App\Models\Conductor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

use App\Filament\Imports\ConductorImporter;
use App\Filament\Exports\ConductorExporter;

class ConductorResource extends Resource
{
    protected static ?string $model = Conductor::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Catálogos';
    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Conductor';
    protected static ?string $pluralLabel = 'Conductores';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Datos Personales')
                ->schema([
                    Forms\Components\TextInput::make('nombre_completo')
                        ->label('Nombre Completo')
                        ->required()
                        ->maxLength(120),

                    Forms\Components\TextInput::make('documento_identidad')
                        ->label('Documento de Identidad')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->length(8)
                        ->numeric(),

                    Forms\Components\TextInput::make('celular')
                        ->label('Celular')
                        ->length(9)
                        ->numeric()
                        ->nullable(),
                ])->columns(3),

            Forms\Components\Section::make('Licencia de Conducir')
                ->schema([
                    Forms\Components\TextInput::make('licencia_numero')
                        ->label('Número de Licencia')
                        ->required(),

                    Forms\Components\TextInput::make('licencia_categoria')
                        ->label('Categoría')
                        ->required(),

                    Forms\Components\DatePicker::make('licencia_vencimiento')
                        ->label('Vencimiento')
                        ->required()
                        ->minDate(today()),
                ])->columns(3),

            Forms\Components\Section::make('Acceso a App (Automático)')
                ->description('El usuario y contraseña serán el documento de identidad.')
                ->schema([
                    Forms\Components\TextInput::make('username_app')
                        ->label('Usuario App')
                        ->disabled()
                        ->dehydrated(false),

                    Forms\Components\TextInput::make('password_hash')
                        ->label('Contraseña App')
                        ->disabled()
                        ->dehydrated(false),
                ]),

            Forms\Components\Section::make('Estado del Conductor')
                ->schema([
                    Forms\Components\Select::make('estado_disponibilidad')
                        ->label('Disponibilidad')
                        ->options([
                            'DISPONIBLE' => 'DISPONIBLE',
                            'OCUPADO'    => 'OCUPADO',
                            'INACTIVO'   => 'INACTIVO',
                        ])
                        ->required()
                        ->default('DISPONIBLE'),

                    Forms\Components\Toggle::make('activo')
                        ->label('Activo')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['username_app']  = $data['documento_identidad'];
        $data['password_hash'] = $data['documento_identidad'];
        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        $data['username_app']  = $data['documento_identidad'];
        $data['password_hash'] = $data['documento_identidad'];
        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('nombre_completo')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('documento_identidad')
                    ->label('DNI'),

                Tables\Columns\TextColumn::make('celular')
                    ->label('Celular'),

                Tables\Columns\BadgeColumn::make('estado_disponibilidad')
                    ->label('Disponibilidad')
                    ->colors([
                        'success' => 'DISPONIBLE',
                        'warning' => 'OCUPADO',
                        'danger'  => 'INACTIVO',
                    ]),

                Tables\Columns\IconColumn::make('activo')
                    ->boolean()
                    ->label('Activo'),

                Tables\Columns\TextColumn::make('licencia_vencimiento')
                    ->label('Licencia Vence')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('estado_disponibilidad')
                    ->label('Disponibilidad')
                    ->options([
                        'DISPONIBLE' => 'DISPONIBLE',
                        'OCUPADO'    => 'OCUPADO',
                        'INACTIVO'   => 'INACTIVO',
                    ]),
            ])

            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\ImportAction::make()
                    ->label('Importar')
                    ->importer(ConductorImporter::class),
                Tables\Actions\ExportAction::make()
                    ->label('Exportar')
                    ->exporter(ConductorExporter::class),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListConductors::route('/'),
            'create' => Pages\CreateConductor::route('/create'),
            'edit'   => Pages\EditConductor::route('/{record}/edit'),
        ];
    }
}
