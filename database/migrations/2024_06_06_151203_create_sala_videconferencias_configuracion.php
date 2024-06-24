<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sala_videoconferencias_configuracion', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('numero_microfonos');
        });

        // Valores predeterminados
        DB::table('sala_videoconferencias_configuracion')->insert([
            'numero_microfonos' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sala_videconferencias_configuracion');
    }
};
