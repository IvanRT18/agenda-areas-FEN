<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EliminarColumnaNombreCompletoDeLaboratorioReservaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laboratorio_reservaciones', function (Blueprint $table) {
            $table->dropColumn('nombre_completo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laboratorio_reservaciones', function (Blueprint $table) {
            $table->string('nombre_completo')->after('rpe');
        });
    }
}
