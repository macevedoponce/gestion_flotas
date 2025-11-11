<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asignaciones_vehiculos', function (Blueprint $table) {
            $table->id('id_asignacion');
            $table->foreignId('id_solicitud')->constrained('solicitud_vehiculo','id_solicitud')->cascadeOnDelete();
            $table->foreignId('id_proyecto')->nullable()->constrained('proyectos','id_proyecto')->cascadeOnDelete();
            $table->foreignId('id_vehiculo')->nullable()->constrained('vehiculos','id_vehiculo')->nullOnDelete();
            $table->foreignId('id_conductor')->nullable()->constrained('conductores','id_conductor')->nullOnDelete();
            $table->foreignId('id_jefe_control')->nullable()->constrained('users','id')->nullOnDelete();

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
