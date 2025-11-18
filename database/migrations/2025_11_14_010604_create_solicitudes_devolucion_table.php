<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('solicitudes_devolucion', function (Blueprint $table) {

            // Identificación
            $table->id('id_devolucion');
            $table->foreignId('id_asignacion')->constrained('asignaciones_vehiculos', 'id_asignacion');
            $table->foreignId('id_usuario_solicitante')->constrained('users', 'id'); // Jefe de Proyecto
            $table->timestamp('fecha_solicitud')->useCurrent();

            // Comentario inicial del Jefe de Proyecto
            $table->text('comentario_solicitante')->nullable();

            // ----------- EVIDENCIAS DEL CONDUCTOR -----------

            $table->foreignId('id_conductor')->nullable()->constrained('conductores', 'id_conductor');

            $table->text('evidencia_foto_km_dev')->nullable();
            $table->text('evidencia_foto_frontal_dev')->nullable();
            $table->text('evidencia_foto_posterior_dev')->nullable();
            $table->text('evidencia_foto_lat_izq_dev')->nullable();
            $table->text('evidencia_foto_lat_der_dev')->nullable();

            $table->json('evidencia_fotos_extra_dev')->nullable();
            $table->text('evidencia_observaciones_dev')->nullable();
            $table->string('evidencia_ubicacion_text_dev')->nullable();

            $table->timestamp('fecha_evidencias_conductor')->nullable();

            // Punto geográfico (PostGIS)
            // Ubicación geográfica de la devolución
            // geography(Point, 4326)
        });

        DB::statement("
            ALTER TABLE solicitudes_devolucion
            ADD COLUMN evidencia_ubicacion_dev geography(Point,4326)
        ");

        DB::statement("
            CREATE INDEX solicitudes_dev_ubicacion_dev_gix
            ON solicitudes_devolucion
            USING GIST (evidencia_ubicacion_dev)
        ");

        Schema::table('solicitudes_devolucion', function (Blueprint $table) {

            // ----------- REVISIÓN DEL JEFE DE PROYECTO -----------

            $table->foreignId('id_usuario_valida_proyecto')->nullable()->constrained('users', 'id');
            $table->text('comentario_valida_proyecto')->nullable();
            $table->timestamp('fecha_valida_proyecto')->nullable();

            // ----------- REVISIÓN FINAL DEL JEFE DE CONTROL -----------

            $table->foreignId('id_usuario_valida_control')->nullable()->constrained('users', 'id');
            $table->text('comentario_valida_control')->nullable();
            $table->timestamp('fecha_valida_control')->nullable();

            // ----------- ESTADO GENERAL DEL FLUJO -----------

            $table->string('estado', 40)
                ->default('PENDIENTE_EVIDENCIAS_CONDUCTOR');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_devolucion');
    }

};
