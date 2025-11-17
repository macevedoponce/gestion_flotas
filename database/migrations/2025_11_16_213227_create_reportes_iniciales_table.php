<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_iniciales', function (Blueprint $table) {
            $table->id('id_reporte_inicial');

            $table->foreignId('id_jornada')
                ->nullable()
                ->constrained('jornadas', 'id_jornada')
                ->nullOnDelete();

            $table->decimal('km_inicial', 12, 2)->nullable();
            $table->text('foto_km_inicial')->nullable();
            $table->text('motivo_traslado')->nullable();
            $table->string('destino', 250)->nullable();
            $table->jsonb('acompanantes')->nullable();
            $table->geometry('ubicacion_inicio', subtype: 'point', srid: 4326)->nullable();

            $table->boolean('checklist_completado')->default(false);

            // CAMPOS DE VALIDACIÓN
            $table->decimal('km_validado', 12, 2)->nullable();
            $table->string('estado_validacion', 20)->default('PENDIENTE');
            $table->text('observacion_validacion')->nullable();

            $table->foreignId('validado_por')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();

            $table->timestamp('validado_en')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes_iniciales');
    }
};
