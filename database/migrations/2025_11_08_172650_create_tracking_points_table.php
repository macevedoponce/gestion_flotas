<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('tracking_points', function (Blueprint $table) {
            $table->id('id_tracking');
            $table->foreignId('id_jornada')->nullable()->constrained('jornadas','id_jornada');
            $table->foreignId('id_conductor')->nullable()->constrained('conductores','id_conductor');
            $table->timestamp('timestamp_ubicacion')->useCurrent();
            // geom geography(point,4326) created via raw SQL
            $table->decimal('velocidad', 8, 2)->nullable();
            $table->decimal('heading', 8, 2)->nullable();
            $table->decimal('precision', 8, 2)->nullable();
            $table->integer('bateria_porcentaje')->nullable();
            $table->string('origen', 20)->default('APP');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE tracking_points ADD COLUMN geom geography(Point,4326) NOT NULL;");
        DB::statement("CREATE INDEX tracking_points_geom_gist ON tracking_points USING GIST (geom);");
    }

    public function down(): void {
        Schema::dropIfExists('tracking_points');
    }
};
