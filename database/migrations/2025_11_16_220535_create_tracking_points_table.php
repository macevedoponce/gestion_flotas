<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_points', function (Blueprint $table) {
            $table->id('id_tracking');

            $table->foreignId('id_jornada')
                ->nullable()
                ->constrained('jornadas', 'id_jornada')
                ->nullOnDelete();

            $table->foreignId('id_conductor')
                ->nullable()
                ->constrained('conductores', 'id_conductor')
                ->nullOnDelete();

            $table->timestamp('timestamp_ubicacion')->default(now());

            $table->geography('geom', subtype: 'point', srid: 4326)->nullable();

            $table->decimal('velocidad', 8, 2)->nullable();
            $table->decimal('heading', 8, 2)->nullable();
            $table->decimal('precision', 8, 2)->nullable();
            $table->integer('bateria_porcentaje')->nullable();
            $table->string('origen', 20)->default('APP');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_points');
    }
};
