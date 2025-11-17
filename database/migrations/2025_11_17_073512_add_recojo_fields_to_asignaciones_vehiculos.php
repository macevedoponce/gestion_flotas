<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asignaciones_vehiculos', function (Blueprint $table) {

            // Evidencias fotográficas
            $table->text('evidencia_foto_km')->nullable();
            $table->text('evidencia_foto_frontal')->nullable();
            $table->text('evidencia_foto_posterior')->nullable();
            $table->text('evidencia_foto_lat_izq')->nullable();
            $table->text('evidencia_foto_lat_der')->nullable();
            $table->json('evidencia_fotos_extra')->nullable();

            // Información adicional
            $table->text('evidencia_observaciones')->nullable();

            // Ubicación GPS (texto y geografía)
            $table->string('evidencia_ubicacion_text', 200)->nullable();

            // Punto geográfico real (opcional por ahora)
            $table->geography('evidencia_ubicacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('asignaciones_vehiculos', function (Blueprint $table) {
            $table->dropColumn([
                'evidencia_foto_km',
                'evidencia_foto_frontal',
                'evidencia_foto_posterior',
                'evidencia_foto_lat_izq',
                'evidencia_foto_lat_der',
                'evidencia_fotos_extra',
                'evidencia_observaciones',
                'evidencia_ubicacion_text',
                'evidencia_ubicacion',
            ]);
        });
    }
};
