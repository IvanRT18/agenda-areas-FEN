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
        Schema::create('laboratorio_citas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('numEquipos');
            $table->string('fechaReservada');
            $table->string('horaReservaInicio');
            $table->string('horaReservaFinal');
            $table->boolean('usaProyector');
            $table->text('reqAdicionales');
            $table->integer('Rpe');
            $table->string('nombreCompleto');
            $table->string('materia');
            $table->string('actividad');
            $table->string('nombreCurso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorio_citas');
    }
};
