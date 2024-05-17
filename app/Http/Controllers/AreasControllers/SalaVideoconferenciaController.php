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

    public function showAll(Request $request)
    {
        $reservaciones = SalaVideoConferenciaModel::all();
        return view('areas.sala-videoconferencias.admin', compact('reservaciones'));
    }

    //Muestra las horas reservadas del día
    public function showHorasDia(Request $request)
    {
        return view('areas.sala-videoconferencias.ver');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = SalaVideoConferenciaModel::where('fecha_reservada', $fecha)->get();
        return response()->json($horasAgendadas);
    }

    // Muestra vista de edición
    public function edit($id){
        $reservacion = SalaVideoConferenciaModel::find($id);
        return view('areas.sala-videoconferencias.editar', compact('reservacion'));
    }

    // Actualiza la reservación
    public function update(Request $request, $id)
    {
        $reservacion = SalaVideoConferenciaModel::find($id);
        $reservacion->id = $request->input('id');
        $reservacion->rpe = session('user');
        $reservacion->fecha_reservada = $request->input('fecha_reservada');
        $reservacion->hora_reserva_inicio = $request->input('hora_reserva_inicio');
        $reservacion->hora_reserva_final = $request->input('hora_reserva_final');
        $reservacion->usa_proyector = $request->input('usa_proyector');
        $reservacion->numero_microfonos = $request->input('numero_microfonos');
        $reservacion->usa_computadora = $request->input('usa_computadora');
        $reservacion->usa_bocinas = $request->input('usa_bocinas');
        $reservacion->update();
        return redirect()->back()->with('status','Reservación actualizada correctamente');
    }

    // Elimina una reservación
    public function delete($id)
    {
        $reservacion = SalaVideoConferenciaModel::find($id);
        $reservacion->delete();
        return redirect()->back()->with('status', 'Reservación borrada correctamente');
    }
}
