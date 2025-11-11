<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Filament\Imports\ProyectoImporter;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProyectoExcelImport;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Gestión Financiera';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('ceco_id')
                ->label('Centro de Costo')
                ->relationship('ceco', 'descripcion_ceco')
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\Select::make('encargado_id')
                ->label('Encargado')
                ->relationship('encargado', 'name')
                ->nullable()
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('anexo')->maxLength(50),
            Forms\Components\TextInput::make('anexo_descripcion')->maxLength(200),
            Forms\Components\TextInput::make('region')->maxLength(100),
            Forms\Components\TextInput::make('unidad_negocio')->maxLength(100),
            Forms\Components\TextInput::make('tipo_flujo')->maxLength(100),
            Forms\Components\TextInput::make('proyecto')->maxLength(200)->required(),
            Forms\Components\DatePicker::make('fecha_inicio'),
            Forms\Components\DatePicker::make('fecha_fin'),
            Forms\Components\Select::make('estado')
                ->options([
                    'ACTIVO' => 'ACTIVO',
                    'INACTIVO' => 'INACTIVO',
                    'CERRADO' => 'CERRADO',
                ])
                ->default('ACTIVO'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ceco.descripcion_ceco')->label('CECO'),
                Tables\Columns\TextColumn::make('proyecto')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('encargado.name')->label('Encargado'),
                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'success' => 'ACTIVO',
                        'warning' => 'INACTIVO',
                        'danger' => 'CERRADO',
                    ]),
                Tables\Columns\TextColumn::make('fecha_inicio')->label('Inicio')->date(),
                Tables\Columns\TextColumn::make('fecha_fin')->label('Fin')->date(),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('exportar')
                    ->label('Exportar Excel/CSV')
                    ->fileName('proyectos_' . now()->format('Ymd_His'))
                    ->defaultFormat('xlsx'),

                Action::make('importar')
                    ->label('Importar Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->form([
                        Forms\Components\FileUpload::make('archivo')
                            ->label('Archivo Excel o CSV')
                            ->required()
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'text/csv',
                                'application/vnd.ms-excel',
                            ]),
                    ])
                    ->action(function (array $data, Action $action) {
                        try {
                            Excel::import(new ProyectoExcelImport, $data['archivo']);
                            Notification::make()
                                ->title('Importación exitosa')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Error al importar')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('exportar')
                    ->label('Exportar seleccionados')
                    ->fileName('proyectos_' . now()->format('Ymd_His'))
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
            'index' => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'edit' => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
