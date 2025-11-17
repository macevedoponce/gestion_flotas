<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChecklistEjecucionResource\Pages;
use App\Models\ChecklistEjecucion;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class ChecklistEjecucionResource extends Resource
{
    protected static ?string $model = ChecklistEjecucion::class;

    protected static ?string $navigationIcon = null; // No aparece en el menú
    protected static bool $shouldRegisterNavigation = false; // Oculto del menú

    protected static ?string $modelLabel = 'Ejecución del Checklist';
    protected static ?string $pluralModelLabel = 'Ejecuciones de Checklist';

    public static function form(Forms\Form $form): Forms\Form
    {
        // Este resource NO se edita con formulario
        return $form;
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        // No mostramos tabla porque solo existen vistas individuales
        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklistEjecucions::route('/'),
            'create' => Pages\CreateChecklistEjecucion::route('/create'),
            'view' => Pages\ViewChecklistEjecucion::route('/{record}'),
            'edit' => Pages\EditChecklistEjecucion::route('/{record}/edit'),
        ];
    }
}
 
