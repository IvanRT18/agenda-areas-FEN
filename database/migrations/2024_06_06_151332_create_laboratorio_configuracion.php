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
        Schema::create('laboratorio_configuracion', function (Blueprint $table) {
            $table->id(); 
            $table->timestamps();
            $table->integer('numero_computadoras');
            $table->integer('parametro_adelante');
        });


        // Valores predeterminados
        DB::table('laboratorio_configuracion')->insert([
            'numero_computadoras' => 40,
            'parametro_adelante' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorio_configuracion');
    }
};
