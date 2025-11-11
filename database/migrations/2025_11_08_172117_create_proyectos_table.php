<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id('id_proyecto');
            $table->foreignId('ceco_id')
                ->constrained('cecos', 'id_ceco')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('anexo', 50)->nullable();
            $table->string('anexo_descripcion', 200)->nullable();
            $table->foreignId('encargado_id')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();
            $table->string('region', 100)->nullable();
            $table->string('unidad_negocio', 100)->nullable();
            $table->string('tipo_flujo', 100)->nullable();
            $table->string('proyecto', 200)->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('estado', 30)->default('ACTIVO');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('proyectos');
    }
};