<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cecos', function (Blueprint $table) {
            $table->id('id_ceco');
            $table->string('codigo', 20)->unique();
            $table->string('descripcion', 150);
            $table->timestamps();
        });

        Schema::create('proyectos', function (Blueprint $table) {
            $table->id('id_proyecto');
            $table->char('codigo_anexo', 14)->unique();
            $table->text('descripcion');
            $table->foreignId('responsable_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('id_ceco')->nullable()->constrained('cecos', 'id_ceco');
            $table->string('lugar_trabajo', 200)->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('estado', 30)->default('ACTIVO');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('proyectos');
        Schema::dropIfExists('cecos');
    }
};
