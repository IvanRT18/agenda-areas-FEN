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
        Schema::create('auditorio_reservaciones', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('rpe');
            $table->string('fecha_reservada');
            $table->string('hora_reserva_inicio');
            $table->string('hora_reserva_final');
            $table->boolean('usa_proyector');
            $table->integer('numero_microfonos');
            $table->boolean('usa_computadora');
            $table->boolean('usa_bocinas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditorio_reservaciones');
    }
};
