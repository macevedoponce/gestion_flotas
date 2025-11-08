<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id('id_vehiculo');
            $table->string('placa', 20)->unique();
            $table->foreignId('id_tipo_vehiculo')->nullable()->constrained('tipos_vehiculo','id_tipo');
            $table->string('marca', 80)->nullable();
            $table->string('modelo', 80)->nullable();
            $table->string('numero_serie', 120)->nullable();
            $table->string('numero_motor', 120)->nullable();
            $table->string('color', 50)->nullable();
            $table->integer('anio')->nullable();
            $table->date('vencimiento_soat')->nullable();
            $table->date('vencimiento_citv')->nullable();
            $table->foreignId('tipo_combustible_id')->nullable()->constrained('tipos_combustible','id_tipo_combustible');
            $table->decimal('km_actual', 12, 2)->default(0);
            // ubicacion_actual will be created as geography via raw SQL below
            $table->string('estado', 30)->default('DISPONIBLE');
            $table->boolean('propio')->default(true);
            $table->string('foto_soat', 120)->nullable();
            $table->string('foto_citv', 120)->nullable();
            $table->string('foto_tarjeta_propiedad', 120)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Add PostGIS geography column and spatial index
        DB::statement("ALTER TABLE vehiculos ADD COLUMN ubicacion_actual geography(Point,4326);");
        DB::statement("CREATE INDEX vehiculos_ubicacion_actual_gist ON vehiculos USING GIST (ubicacion_actual);");
    }

    public function down(): void {
        Schema::dropIfExists('vehiculos');
    }
};
