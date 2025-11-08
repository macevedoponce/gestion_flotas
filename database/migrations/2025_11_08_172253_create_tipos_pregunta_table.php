<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('tipos_pregunta', function (Blueprint $table) {
            $table->id('id_tipo_pregunta');
            $table->string('nombre', 50);
            $table->string('estructura_respuesta', 20);
            $table->timestamps();
        });

        // Seed initial types (same as original)
        DB::table('tipos_pregunta')->insert([
            ['nombre'=>'si_no','estructura_respuesta'=>'booleano','created_at'=>now(),'updated_at'=>now()],
            ['nombre'=>'texto','estructura_respuesta'=>'texto','created_at'=>now(),'updated_at'=>now()],
            ['nombre'=>'numero','estructura_respuesta'=>'numero','created_at'=>now(),'updated_at'=>now()],
            ['nombre'=>'fecha','estructura_respuesta'=>'fecha','created_at'=>now(),'updated_at'=>now()],
            ['nombre'=>'imagen','estructura_respuesta'=>'imagen','created_at'=>now(),'updated_at'=>now()],
            ['nombre'=>'firma','estructura_respuesta'=>'imagen','created_at'=>now(),'updated_at'=>now()],
            ['nombre'=>'opcion_multiple','estructura_respuesta'=>'texto','created_at'=>now(),'updated_at'=>now()],
            ['nombre'=>'multiple_campos','estructura_respuesta'=>'json','created_at'=>now(),'updated_at'=>now()],
            ['nombre'=>'nivel_combustible','estructura_respuesta'=>'json','created_at'=>now(),'updated_at'=>now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('tipos_pregunta');
    }
};
