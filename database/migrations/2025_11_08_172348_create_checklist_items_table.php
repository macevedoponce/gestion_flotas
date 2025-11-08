<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id('id_item');
            $table->foreignId('id_seccion')->nullable()->constrained('checklist_secciones','id_seccion');
            $table->foreignId('id_tipo_pregunta')->nullable()->constrained('tipos_pregunta','id_tipo_pregunta');
            $table->text('pregunta');
            $table->text('descripcion')->nullable();
            $table->boolean('obligatorio')->default(false);
            $table->integer('orden')->default(0);
            $table->json('configuracion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('checklist_items');
    }
};
