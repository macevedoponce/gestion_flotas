<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abastecimientos', function (Blueprint $table) {
            $table->id('id_abastecimiento');

            $table->foreignId('id_jornada')
                ->nullable()
                ->constrained('jornadas', 'id_jornada')
                ->nullOnDelete();

            $table->foreignId('id_conductor')
                ->nullable()
                ->constrained('conductores', 'id_conductor')
                ->nullOnDelete();

            $table->timestamp('fecha')->default(now());

            $table->decimal('km_reportado', 12, 2)->nullable();

            $table->text('foto_tablero_antes')->nullable();
            $table->text('foto_surtidor_cero')->nullable();
            $table->text('foto_tablero_despues')->nullable();
            $table->text('foto_surtidor_final')->nullable();
            $table->text('foto_comprobante')->nullable();

            $table->geometry('ubicacion', subtype: 'point', srid: 4326)->nullable();

            $table->string('estado_verificacion', 20)->default('PENDIENTE');
            $table->foreignId('verificado_por')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();

            $table->text('observacion_verificacion')->nullable();
            $table->timestamp('fecha_verificacion')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abastecimientos');
    }
};
