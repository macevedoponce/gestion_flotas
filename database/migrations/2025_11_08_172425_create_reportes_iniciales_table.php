<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('reportes_iniciales', function (Blueprint $table) {
            $table->id('id_reporte_inicial');
            $table->foreignId('id_jornada')->nullable()->constrained('jornadas','id_jornada');
            $table->decimal('km_inicial', 12, 2)->nullable();
            $table->text('foto_km_inicial')->nullable();
            $table->text('motivo_traslado')->nullable();
            $table->string('destino', 250)->nullable();
            $table->integer('cantidad_acompanantes')->default(0);
            $table->json('acompanantes')->nullable();
            // ubicacion_inicio will be geography
            $table->boolean('checklist_completado')->default(false);
            $table->timestamp('fecha_reporte')->useCurrent();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE reportes_iniciales ADD COLUMN ubicacion_inicio geography(Point,4326);");
        DB::statement("CREATE INDEX reportes_iniciales_ubicacion_inicio_gist ON reportes_iniciales USING GIST (ubicacion_inicio);");
    }

    public function down(): void {
        Schema::dropIfExists('reportes_iniciales');
    }
};
