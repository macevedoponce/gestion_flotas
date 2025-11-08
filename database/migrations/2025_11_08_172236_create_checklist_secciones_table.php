<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_secciones', function (Blueprint $table) {
            $table->id('id_seccion');
            $table->foreignId('id_plantilla')->nullable()->constrained('checklist_plantillas','id_plantilla');
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('checklist_secciones');
    }
};
