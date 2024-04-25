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
        Schema::create('sala_videoconferencia_reservaciones', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('Rpe');
            $table->boolean('usaProyector');
            $table->string('fechaReservada');
            $table->string('horaReservaInicio');
            $table->string('horaReservaFinal');
            $table->integer('numeroMicrofonos');
            $table->boolean('usaComputadora');
            $table->boolean('usaBocinas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sala_videoconferencia_reservaciones');
    }
};
