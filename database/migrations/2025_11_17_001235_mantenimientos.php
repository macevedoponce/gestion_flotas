<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id('id_mantenimiento');

            // VEHÍCULO
            $table->foreignId('id_vehiculo')
                ->constrained('vehiculos', 'id_vehiculo')
                ->cascadeOnDelete();

            // CONTROL BÁSICO
            $table->string('tipo', 20)->default('PREVENTIVO'); // PREVENTIVO / CORRECTIVO
            $table->date('fecha_ingreso');
            $table->date('fecha_estimada_entrega')->nullable();
            $table->date('fecha_entrega_real')->nullable();
            $table->decimal('km_registrado', 12, 2)->nullable();

            // TALLER
            $table->string('taller_nombre', 150)->nullable();
            $table->string('taller_contacto', 50)->nullable();

            // MOTIVO Y TRABAJOS
            $table->text('motivo')->nullable();
            $table->json('trabajos')->nullable();
            $table->decimal('costo_estimado', 12, 2)->nullable();
            $table->decimal('costo_real', 12, 2)->nullable();

            // ESTADO GENERAL
            $table->string('estado', 30)->default('EN_PROCESO');
            // PROGRAMADO / EN_PROCESO / PRORROGA_SOLICITADA / FINALIZADO / CANCELADO

            // PRÓRROGAS
            $table->date('fecha_solicitud_prorroga')->nullable();
            $table->text('motivo_prorroga')->nullable();
            $table->date('nueva_fecha_entrega')->nullable();
            $table->string('estado_prorroga', 20)->nullable(); 
            // PENDIENTE / APROBADA / RECHAZADA

            // EVIDENCIAS
            $table->json('fotos')->nullable();
            $table->json('documentos')->nullable();

            // QUIÉN CREA / APRUEBA
            $table->foreignId('creado_por')->nullable()->constrained('users', 'id');
            $table->foreignId('aprobado_por')->nullable()->constrained('users', 'id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};
