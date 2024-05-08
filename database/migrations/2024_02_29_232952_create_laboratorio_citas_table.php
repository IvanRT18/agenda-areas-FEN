<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laboratorio_reservaciones', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('rpe');
            $table->string('nombre_completo');
            $table->string('fecha_reservada');
            $table->string('hora_reserva_inicio');
            $table->string('hora_reserva_final');
            $table->integer('numero_equipos');
            $table->boolean('usa_proyector');
            $table->text('req_adicionales')->nullable();
            $table->string('materia');
            $table->string('actividad');
            $table->string('nombre_curso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorio_reservaciones');
    }
};
