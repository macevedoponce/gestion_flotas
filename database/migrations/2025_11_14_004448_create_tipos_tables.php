<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tipos_vehiculo', function (Blueprint $table) {
            $table->id('id_tipo');
            $table->string('nombre', 80);
            $table->text('descripcion')->nullable();
            $table->integer('capacidad_personas')->default(1);
            $table->decimal('capacidad_tanque', 10, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('tipos_combustible', function (Blueprint $table) {
            $table->id('id_tipo_combustible');
            $table->string('nombre', 50);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tipos_combustible');
        Schema::dropIfExists('tipos_vehiculo');
    }
};
