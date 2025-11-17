<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asignacion_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_asignacion');
            $table->unsignedBigInteger('id_usuario')->nullable();

            $table->string('accion'); // Cambio de conductor, reasignación de vehículo, etc.

            $table->json('detalles')->nullable(); // De qué a qué cambió

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignacion_logs');
    }
};
