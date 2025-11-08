<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_plantillas', function (Blueprint $table) {
            $table->id('id_plantilla');
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->foreignId('id_tipo_vehiculo')->nullable()->constrained('tipos_vehiculo','id_tipo');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('checklist_plantillas');
    }
};
