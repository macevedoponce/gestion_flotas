<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id('id_proyecto');
            $table->char('codigo_anexo', 14)->unique()->nullable();
            $table->text('descripcion');
            $table->foreignId('responsable_id')->nullable()->constrained('users','id');
            $table->string('lugar_trabajo', 200)->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('estado',30)->default('ACTIVO');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('proyectos');
    }
};
