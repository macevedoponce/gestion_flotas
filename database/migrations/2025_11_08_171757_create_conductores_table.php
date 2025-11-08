<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('conductores', function (Blueprint $table) {
            $table->id('id_conductor');
            $table->string('nombre_completo', 150);
            $table->string('documento_identidad', 50)->unique()->nullable();
            $table->string('celular', 30)->nullable();
            $table->string('licencia_numero', 80)->nullable();
            $table->string('licencia_categoria', 20)->nullable();
            $table->date('licencia_vencimiento')->nullable();
            $table->string('username_app', 100)->unique()->nullable();
            $table->text('password_hash')->nullable();
            $table->string('estado_disponibilidad', 20)->default('DISPONIBLE');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('conductores');
    }
};
