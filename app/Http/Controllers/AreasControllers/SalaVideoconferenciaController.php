<?php

namespace App\Http\Controllers\AreasControllers;

use App\Http\Controllers\Controller;
use App\Models\AreasModels\SalaVideoConferenciaModel;
use Illuminate\Http\Request;

class SalaVideoconferenciaController extends Controller
{
    public function store(Request $request)
    {
        $reservacion = new SalaVideoConferenciaModel();
        $reservacion->rpe = session('user');
        $reservacion->fecha_reservada = $request->fecha_reservada_sala_video;
        $reservacion->hora_reserva_inicio = $request->hora_reserva_inicio_sala_video;
        $reservacion->hora_reserva_final = $request->hora_reserva_final_sala_video;
        $reservacion->numero_microfonos = $request->numero_microfonos_sala_video;

        if ($request->usa_proyector_sala_video == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usa_proyector = true;
        else
            $reservacion->usa_proyector = false;

        if ($request->usa_computadora_sala_video == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usa_computadora = true;
        else
            $reservacion->usa_computadora = false;

        if ($request->usa_bocinas_sala_video == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usa_bocinas = true;
        else
            $reservacion->usa_bocinas = false;

        $reservacion->save();

        return redirect()->back()->with('status', 'Se ha reservado el aula magna correctamente');
    }

    //Muestra las horas reservadas del día
    public function showHorasDia(Request $request)
    {
        return view('areas.sala_videoconferencia_reservaciones');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = SalaVideoConferenciaModel::where('fechaReservada', $fecha)->get();
        return response()->json($horasAgendadas);
    }
}
