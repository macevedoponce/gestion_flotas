<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('solicitud_vehiculo', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->char('codigo_anexo', 14)->nullable();
            $table->text('descripcion_proyecto')->nullable();
            $table->foreignId('id_usuario_solicitante')->nullable()->constrained('users','id');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->foreignId('id_tipo_vehiculo')->nullable()->constrained('tipos_vehiculo','id_tipo');
            $table->text('motivo_trabajo')->nullable();
            $table->string('lugar_trabajo', 200)->nullable();
            $table->integer('cantidad_dias')->nullable();
            $table->boolean('indeterminado')->default(false);
            $table->boolean('requiere_conductor')->default(true);
            $table->string('conductor_externo_nombres',150)->nullable();
            $table->string('conductor_externo_dni',50)->nullable();
            $table->string('conductor_externo_celular',30)->nullable();
            $table->string('conductor_externo_licencia',80)->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('estado', 30)->default('PENDIENTE');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('solicitud_vehiculo');
    }
};
