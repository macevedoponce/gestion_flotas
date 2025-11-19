<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_vehiculo', function (Blueprint $table) {
            $table->boolean('conductor_creado_externo')
                ->default(false)
                ->after('conductor_externo_licencia');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes_vehiculo', function (Blueprint $table) {
            $table->dropColumn('conductor_creado_externo');
        });
    }
};

