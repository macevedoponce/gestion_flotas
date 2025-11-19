<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conductores', function (Blueprint $table) {
            $table->string('tipo_conductor', 20)
                ->default('INTERNO')
                ->after('password_hash'); // ajusta posición si quieres
        });
    }

    public function down(): void
    {
        Schema::table('conductores', function (Blueprint $table) {
            $table->dropColumn('tipo_conductor');
        });
    }
};

