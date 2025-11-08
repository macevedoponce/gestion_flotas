<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('abastecimientos', function (Blueprint $table) {
            $table->id('id_abastecimiento');
            $table->foreignId('id_jornada')->nullable()->constrained('jornadas','id_jornada');
            $table->foreignId('id_conductor')->nullable()->constrained('conductores','id_conductor');
            $table->timestamp('fecha')->useCurrent();
            $table->decimal('km_reportado', 12, 2)->nullable();
            $table->text('foto_tablero_antes')->nullable();
            $table->text('foto_surtidor_cero')->nullable();
            $table->text('foto_tablero_despues')->nullable();
            $table->text('foto_surtidor_final')->nullable();
            $table->text('foto_comprobante')->nullable();
            // ubicacion geography
            $table->string('estado_verificacion',20)->default('PENDIENTE');
            $table->foreignId('verificado_por')->nullable()->constrained('users','id');
            $table->timestamp('fecha_verificacion')->nullable();
            $table->decimal('km_validado', 12, 2)->nullable();
            $table->decimal('litros_validado', 10, 2)->nullable();
            $table->decimal('precio_total_validado', 12, 2)->nullable();
            $table->string('codigo_comprobante',100)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE abastecimientos ADD COLUMN ubicacion geography(Point,4326);");
        DB::statement("CREATE INDEX abastecimientos_ubicacion_gist ON abastecimientos USING GIST (ubicacion);");
    }

    public function down(): void {
        Schema::dropIfExists('abastecimientos');
    }
};
