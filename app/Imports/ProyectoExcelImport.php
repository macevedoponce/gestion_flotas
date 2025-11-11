<?php

namespace App\Imports;

use App\Models\Proyecto;
use App\Models\Ceco;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class ProyectoExcelImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $ceco = Ceco::where('codigo_ceco', $row['codigo_ceco'] ?? null)->first();

        if (!$ceco) {
            return null; // Ignora filas sin CECO válido
        }

        return new Proyecto([
            'ceco_id' => $ceco->id_ceco,
            'proyecto' => $row['proyecto'] ?? null,
            'region' => $row['region'] ?? null,
            'unidad_negocio' => $row['unidad_negocio'] ?? null,
            'tipo_flujo' => $row['tipo_flujo'] ?? null,
            'anexo' => $row['anexo'] ?? null,
            'anexo_descripcion' => $row['anexo_descripcion'] ?? null,
            'fecha_inicio' => $row['fecha_inicio'] ?? null,
            'fecha_fin' => $row['fecha_fin'] ?? null,
            'estado' => Str::upper($row['estado'] ?? 'ACTIVO'),
        ]);
    }
}
