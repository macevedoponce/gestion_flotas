<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asignaciones_vehiculos', function (Blueprint $table) {
            $table->id('id_asignacion');
            $table->foreignId('id_solicitud')->nullable()->constrained('solicitud_vehiculo','id_solicitud');
            $table->foreignId('id_proyecto')->nullable()->constrained('proyectos','id_proyecto');
            $table->foreignId('id_vehiculo')->nullable()->constrained('vehiculos','id_vehiculo');
            $table->foreignId('id_conductor')->nullable()->constrained('conductores','id_conductor');
            $table->foreignId('id_jefe_control')->nullable()->constrained('users','id');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamp('fecha_finalizacion')->nullable();
            $table->string('estado',30)->default('ACTIVA');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asignaciones_vehiculos');
    }
};
