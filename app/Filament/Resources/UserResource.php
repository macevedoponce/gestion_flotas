<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationLabel = 'Usuarios del Sistema';
    protected static ?string $pluralLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Usuario')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre completo')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required(),

                        Forms\Components\TextInput::make('telefono')
                            ->label('Teléfono')
                            ->maxLength(30)
                            ->tel(),

                        Forms\Components\Toggle::make('activo')
                            ->label('Activo')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Seguridad')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->autocomplete('new-password')
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->required(fn ($context) => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->minLength(8)
                            ->maxLength(255)
                            ->placeholder('Dejar en blanco para mantener la contraseña actual'),
                    ]),

                Forms\Components\Section::make('Roles y Permisos')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->label('Roles asignados')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable(),

                        // Si deseas agregar permisos directos (no siempre recomendable):
                        // Forms\Components\Select::make('permissions')
                        //     ->label('Permisos directos')
                        //     ->multiple()
                        //     ->relationship('permissions', 'name')
                        //     ->searchable()
                        //     ->preload(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->toggleable(),

                Tables\Columns\TagsColumn::make('roles.name')
                    ->label('Roles')
                    ->sortable(),

                Tables\Columns\IconColumn::make('activo')
                    ->boolean()
                    ->label('Activo')
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Rol')
                    ->relationship('roles', 'name'),

                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Activo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('activar')
                    ->label('Activar')
                    ->color('success')
                    ->visible(fn (User $record) => ! $record->activo)
                    ->action(fn (User $record) => $record->update(['activo' => true])),

                Tables\Actions\Action::make('desactivar')
                    ->label('Desactivar')
                    ->color('warning')
                    ->visible(fn (User $record) => $record->activo)
                    ->requiresConfirmation()
                    ->action(fn (User $record) => $record->update(['activo' => false])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/crear'),
            'edit' => Pages\EditUser::route('/{record}/editar'),
        ];
    }
}
