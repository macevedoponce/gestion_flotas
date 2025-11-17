<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_finales', function (Blueprint $table) {
            $table->id('id_reporte_final');

            $table->foreignId('id_jornada')
                ->nullable()
                ->constrained('jornadas', 'id_jornada')
                ->nullOnDelete();

            $table->decimal('km_final', 12, 2)->nullable();
            $table->text('foto_km_final')->nullable();
            $table->jsonb('fotos_adicionales')->nullable();

            $table->text('observaciones')->nullable();

            $table->geometry('ubicacion_fin', subtype: 'point', srid: 4326)->nullable();

            $table->decimal('horas_totales', 8, 2)->nullable();

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
        Schema::dropIfExists('reportes_finales');
    }
};
