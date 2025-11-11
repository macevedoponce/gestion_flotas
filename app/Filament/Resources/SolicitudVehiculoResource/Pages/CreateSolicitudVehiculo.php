<?php


namespace App\Filament\Resources\SolicitudVehiculoResource\Pages;

use App\Filament\Resources\SolicitudVehiculoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSolicitudVehiculo extends CreateRecord
{
    protected static string $resource = SolicitudVehiculoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id_usuario_solicitante'] = Auth::id();
        $data['estado'] = 'PENDIENTE';
        return $data;
    }
}
