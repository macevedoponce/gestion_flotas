<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('solicitudes_devolucion', function (Blueprint $table) {
            $table->id('id_devolucion');
            $table->foreignId('id_asignacion')->nullable()->constrained('asignaciones_vehiculos','id_asignacion');
            $table->foreignId('id_usuario_solicitante')->nullable()->constrained('users','id');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->json('fotos_evidencia')->nullable();
            $table->json('videos_evidencia')->nullable();
            // ubicacion_entrega geography
            $table->string('ubicacion_text', 250)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('estado',20)->default('PENDIENTE');
            $table->foreignId('validado_por')->nullable()->constrained('users','id');
            $table->timestamp('fecha_revision')->nullable();
            $table->text('comentarios_revision')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE solicitudes_devolucion ADD COLUMN ubicacion_entrega geography(Point,4326);");
        DB::statement("CREATE INDEX solicitudes_devolucion_ubicacion_entrega_gist ON solicitudes_devolucion USING GIST (ubicacion_entrega);");
    }

    public function down(): void {
        Schema::dropIfExists('solicitudes_devolucion');
    }
};
