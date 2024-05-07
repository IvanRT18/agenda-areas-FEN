<?php

namespace App\Http\Controllers\AreasControllers;

use App\Models\AreasModels\LaboratorioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\AreasModels\AulaMagnaModel;

class AulaMagnaController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        $reservacion = new AulaMagnaModel();
        $reservacion->rpe = session('user');
        $reservacion->fecha_reservada = $request->fecha_reservada_magna;
        $reservacion->hora_reserva_inicio = $request->hora_reserva_inicio_magna;
        $reservacion->hora_reserva_final = $request->hora_reserva_final_magna;
        $reservacion->numero_microfonos = $request->numero_microfonos_magna;

        if ($request->usa_proyector_magna == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usa_proyector = true;
        else
            $reservacion->usa_proyector = false;

        if ($request->usa_computadora_magna == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usa_computadora = true;
        else
            $reservacion->usa_computadora = false;

        if ($request->usa_bocinas_magna == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usa_bocinas = true;
        else
            $reservacion->usa_bocinas = false;

        $reservacion->save();

        return redirect()->back()->with('status', 'Se ha reservado el aula magna correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = AulaMagnaModel::all();
        return view('areas.aula-magna.admin', compact('reservaciones'));
    }

    public function showHorasDia(Request $request)
    {
        // $reservaciones = LaboratorioModel::where('fechaReservada', $fecha)->get();
        return view('areas.aula-magna.ver');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = AulaMagnaModel::where('fecha_reservada', $fecha)->get();

        return response()->json($horasAgendadas);
    }

    // Edita una cita
    public function edit($id){
        $reservacion = AulaMagnaModel::find($id);
        return view('areas.aula-magna.editar', compact('reservacion'));
    }

    public function update(Request $request, $id)
    {
        $reservacion = AulaMagnaModel::find($id);
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

    public function delete($id)
    {
        $reservacion = AulaMagnaModel::find($id);
        $reservacion->delete();
        return redirect()->back()->with('status', 'Reservación borrada correctamente');
    }

    public function diasInhabiles()
    {
        return view('diasDesactivados');
    }
}
