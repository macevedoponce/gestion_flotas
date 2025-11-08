<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('reportes_finales', function (Blueprint $table) {
            $table->id('id_reporte_final');
            $table->foreignId('id_jornada')->nullable()->constrained('jornadas','id_jornada');
            $table->decimal('km_final', 12, 2)->nullable();
            $table->text('foto_km_final')->nullable();
            $table->json('fotos_adicionales')->nullable();
            // ubicacion_fin geography
            $table->text('observaciones')->nullable();
            $table->boolean('es_jornada_extendida')->default(false);
            $table->decimal('horas_totales', 8, 2)->nullable();
            $table->timestamp('fecha_reporte')->useCurrent();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE reportes_finales ADD COLUMN ubicacion_fin geography(Point,4326);");
        DB::statement("CREATE INDEX reportes_finales_ubicacion_fin_gist ON reportes_finales USING GIST (ubicacion_fin);");
    }

    public function down(): void {
        Schema::dropIfExists('reportes_finales');
    }
};
