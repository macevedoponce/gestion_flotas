<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cecos', function (Blueprint $table) {
            $table->id('id_ceco');
            $table->char('codigo_ceco', 14)->unique();
            $table->string('descripcion_ceco', 200);
            $table->foreignId('responsable_id')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();
            $table->string('tipo_ceco', 50)->nullable(); // OPEX / CAPEX
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('cecos');
    }
};
