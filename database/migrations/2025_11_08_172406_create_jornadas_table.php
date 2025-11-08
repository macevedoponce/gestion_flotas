<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jornadas', function (Blueprint $table) {
            $table->id('id_jornada');
            $table->foreignId('id_asignacion')->nullable()->constrained('asignaciones_vehiculos','id_asignacion');
            $table->foreignId('id_conductor')->nullable()->constrained('conductores','id_conductor');
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_fin')->nullable();
            $table->date('dia_operativo');
            $table->string('estado', 20)->default('EN_CURSO');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('jornadas');
    }
};
