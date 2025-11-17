<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * 1) TIPOS DE PREGUNTA
         */
        Schema::create('checklist_tipos_pregunta', function (Blueprint $table) {
            $table->id('id_tipo_pregunta');
            $table->string('codigo', 50)->unique(); // BOOLEANO, TEXTO, NUMERICO, OPCIONES, IMAGEN, etc.
            $table->string('nombre', 100);
            $table->timestamps();
        });

        /**
         * 2) PLANTILLAS DE CHECKLIST
         */
        Schema::create('checklist_plantillas', function (Blueprint $table) {
            $table->id('id_plantilla');
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();

            // RELACIÓN CON TIPO DE VEHÍCULO
            $table->foreignId('id_tipo_vehiculo')
                ->nullable()
                ->constrained('tipos_vehiculo', 'id_tipo')
                ->nullOnDelete();

            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        /**
         * 3) SECCIONES
         */
        Schema::create('checklist_secciones', function (Blueprint $table) {
            $table->id('id_seccion');
            $table->foreignId('id_plantilla')
                ->constrained('checklist_plantillas', 'id_plantilla')
                ->cascadeOnDelete();

            $table->string('nombre', 150);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        /**
         * 4) ITEMS / PREGUNTAS
         */
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id('id_item');
            $table->foreignId('id_seccion')
                ->constrained('checklist_secciones', 'id_seccion')
                ->cascadeOnDelete();

            $table->foreignId('id_tipo_pregunta')
                ->constrained('checklist_tipos_pregunta', 'id_tipo_pregunta')
                ->cascadeOnDelete();

            $table->text('pregunta');
            $table->boolean('obligatorio')->default(false);

            $table->json('configuracion')->nullable(); // opciones, rangos, etc.
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        /**
         * 5) EJECUCIONES DE CHECKLIST
         */
        Schema::create('checklist_ejecuciones', function (Blueprint $table) {
            $table->id('id_ejecucion');

            $table->foreignId('id_plantilla')
                ->constrained('checklist_plantillas', 'id_plantilla')
                ->cascadeOnDelete();

            $table->foreignId('id_reporte_inicial')
                ->unique() // 1 checklist por reporte inicial
                ->constrained('reportes_iniciales', 'id_reporte_inicial')
                ->cascadeOnDelete();

            $table->foreignId('id_jornada')
                ->nullable()
                ->constrained('jornadas', 'id_jornada')
                ->nullOnDelete();

            $table->foreignId('id_vehiculo')
                ->nullable()
                ->constrained('vehiculos', 'id_vehiculo')
                ->nullOnDelete();

            $table->foreignId('id_conductor')
                ->nullable()
                ->constrained('conductores', 'id_conductor')
                ->nullOnDelete();

            $table->timestamp('fecha_ejecucion')->default(now());
            $table->string('estado', 20)->default('COMPLETADO');
            $table->timestamps();
        });

        /**
         * 6) RESPUESTAS INDIVIDUALES
         */
        Schema::create('checklist_respuestas', function (Blueprint $table) {
            $table->id('id_respuesta');

            $table->foreignId('id_ejecucion')
                ->constrained('checklist_ejecuciones', 'id_ejecucion')
                ->cascadeOnDelete();

            $table->foreignId('id_item')
                ->constrained('checklist_items', 'id_item')
                ->cascadeOnDelete();

            // RESPUESTAS
            $table->text('valor_texto')->nullable();
            $table->decimal('valor_numero', 12, 2)->nullable();
            $table->boolean('valor_booleano')->nullable();
            $table->json('valor_json')->nullable(); // select multiplo
            $table->string('valor_imagen', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_respuestas');
        Schema::dropIfExists('checklist_ejecuciones');
        Schema::dropIfExists('checklist_items');
        Schema::dropIfExists('checklist_secciones');
        Schema::dropIfExists('checklist_plantillas');
        Schema::dropIfExists('checklist_tipos_pregunta');
    }
};
