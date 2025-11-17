<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_emergencia', function (Blueprint $table) {
            $table->id('id_evento');

            // Relaciones existentes en tu BD
            $table->foreignId('id_jornada')
                ->nullable()
                ->constrained('jornadas', 'id_jornada')
                ->nullOnDelete();

            $table->foreignId('id_conductor')
                ->nullable()
                ->constrained('conductores', 'id_conductor')
                ->nullOnDelete();

            $table->foreignId('id_vehiculo')
                ->nullable()
                ->constrained('vehiculos', 'id_vehiculo')
                ->nullOnDelete();

            $table->foreignId('id_tipo_evento')
                ->nullable()
                ->constrained('tipos_evento_emergencia', 'id_tipo_evento')
                ->nullOnDelete();

            // Datos del evento
            $table->text('descripcion')->nullable();
            $table->json('fotos')->nullable();

            $table->geography('ubicacion', subtype: 'point', srid: 4326)->nullable();

            $table->timestamp('hora_reporte')->default(now());

            // Estado del evento
            $table->string('estado', 20)->default('PENDIENTE');

            // Validación/cierre
            $table->foreignId('atendido_por')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();

            $table->text('comentario_cierre')->nullable();
            $table->timestamp('hora_cierre')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_emergencia');
    }
};
