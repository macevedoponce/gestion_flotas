<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tipos_combustible', function (Blueprint $table) {
            $table->id('id_tipo_combustible');
            $table->string('nombre', 50);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tipos_combustible');
    }
};
