<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_respuestas', function (Blueprint $table) {
            $table->id('id_respuesta');
            $table->foreignId('id_reporte_inicial')->nullable()->constrained('reportes_iniciales','id_reporte_inicial');
            $table->foreignId('id_item')->nullable()->constrained('checklist_items','id_item');
            $table->text('valor_texto')->nullable();
            $table->decimal('valor_numero', 12, 4)->nullable();
            $table->boolean('valor_booleano')->nullable();
            $table->date('valor_fecha')->nullable();
            $table->json('valor_json')->nullable();
            $table->text('valor_imagen')->nullable();
            $table->timestamp('fecha_respuesta')->useCurrent();
            $table->timestamps();
        });

        // index to speed up queries by item/reporte
        Schema::table('checklist_respuestas', function (Blueprint $table) {
            $table->index(['id_item','id_reporte_inicial']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('checklist_respuestas');
    }
};
