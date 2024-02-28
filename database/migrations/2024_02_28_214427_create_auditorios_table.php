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
        Schema::create('auditorios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('equiposComputo');
            $table->string('fechaAgendada');
            $table->string('horaAgendada');
            $table->boolean('usaProyector');
            $table->text('reqAdicionales');
            $table->integer('Rpe');
            $table->string('nombreCompleto');
            $table->string('materia');
            $table->string('tipoActividad');
            $table->string('nombreActividad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditorios');
    }
};
